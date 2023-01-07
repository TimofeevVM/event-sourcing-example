<?php

declare(strict_types=1);

namespace Shared\Infrastructure\EventSourcing\EventStore\Dbal;

use Doctrine\DBAL\Connection;
use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\Bus\Event\Deserializer;
use Shared\Domain\Bus\Event\EventException;
use Shared\Domain\Bus\Event\EventStream;
use Shared\Domain\Bus\Event\Serializer;
use Shared\Domain\EventSourcing\EventStore\EventStore;

class DbalEventStore implements EventStore
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Serializer $serializer,
        private readonly Deserializer $deserializer,
        private readonly string $tableName = 'event_store',
    ) {
    }

    public function append(EventStream $eventStream): EventStore
    {
        $this->connection->beginTransaction();

        try {
            $version = $this->getCurrentVersion($eventStream->aggregateId);

            foreach ($eventStream as $event) {
                $this->connection->insert(
                    $this->tableName,
                    [
                        'id' => $event->getEventId(),
                        'aggregate_id' => $event->getAggregateId(),
                        'version' => $version++,
                        'occurred_on' => $event->getOccurredOn()->format(\DateTime::ATOM),
                        'payload' => $this->serializer->serialize($event),
                    ]
                );
            }

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            throw new DbalEventStoreNotAppend('', 0, $exception);
        }

        return $this;
    }

    public function getEventStream(AggregateId $aggregateId): EventStream
    {
        return $this->getEventStreamFromVersion($aggregateId, 0);
    }

    public function getCurrentVersion(AggregateId $aggregateId): int
    {
        return (int) $this->connection->fetchOne(
            'SELECT MAX(version)'
            .' FROM '.$this->tableName
            .' WHERE "aggregate_id" = ?',
            [$aggregateId->value()]
        );
    }

    public function getEventStreamFromVersion(AggregateId $aggregateId, int $version): EventStream
    {
        $rawEvents = $this->connection->fetchAllAssociative(
            'SELECT "id", "aggregate_id", "occurred_on", "version", "payload" '
            .' FROM '.$this->tableName
            .' WHERE "aggregate_id" = ?'
            .' AND "version" >= ?'
            .' ORDER BY "version" ASC',
            [$aggregateId->value(), $version]
        );

        $eventStream = new EventStream($aggregateId);
        foreach ($rawEvents as $rawEvent) {
            try {
                $event = $this->deserializer->deserialize((string) $rawEvent['payload']);
            } catch (EventException) {
                continue;
            }

            $eventStream->append($event);
        }

        return $eventStream;
    }
}
