<?php

declare(strict_types=1);

namespace Tests\Shared\Integration\Infrastructure\Bus\Event\EventStore\Dbal;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerBuilder;
use Shared\Domain\Bus\Event\EventStream;
use Shared\Infrastructure\Bus\Event\EventStore\Dbal\DbalEventStore;
use Shared\Infrastructure\Bus\Event\Serializer\JMS\JMSSerializer;
use Shared\Infrastructure\Tests\PhpUnit\InfrastructureTestCase;
use Tests\Shared\Integration\Infrastructure\Bus\Event\EventStore\Dbal\Support\AggregateIdTested;
use Tests\Shared\Integration\Infrastructure\Bus\Event\EventStore\Dbal\Support\EventTested;

class DbalEventStoreTest extends InfrastructureTestCase
{
    private \Doctrine\DBAL\Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->get(EntityManagerInterface::class);

        $this->connection = $entityManager->getConnection();
    }

    public function testAppend(): void
    {
        $serializer = new JMSSerializer(SerializerBuilder::create()->build());
        $eventStore = new DbalEventStore(
            $this->connection,
            $serializer,
            $serializer,
        );

        $aggregateId = AggregateIdTested::random();
        $eventStream = new EventStream($aggregateId);

        $title1 = 'title change 1';
        $event1 = new EventTested((string) $aggregateId, $title1);
        $eventStream->append($event1);

        $title2 = 'title change 2';
        $event2 = new EventTested((string) $aggregateId, $title2);
        $eventStream->append($event2);

        $title3 = 'title change 3';
        $event3 = new EventTested((string) $aggregateId, $title3);
        $eventStream->append($event3);

        $eventStore->append($eventStream);

        $eventStreamFromDb = $eventStore->getEventStream($aggregateId);

        $this->assertSame((string) $aggregateId, (string) $eventStreamFromDb->aggregateId);
        $this->assertCount($eventStream->count(), $eventStreamFromDb);

        $events = [];
        foreach ($eventStreamFromDb as $event) {
            $events[$event->getEventId()] = $event;
        }

        $this->assertInstanceOf(EventTested::class, $events[$event1->getEventId()]);
        $this->assertInstanceOf(EventTested::class, $events[$event2->getEventId()]);
        $this->assertInstanceOf(EventTested::class, $events[$event3->getEventId()]);

        /**
         * @var EventTested $eventDb
         */
        $eventDb = $events[$event1->getEventId()];
        $this->assertSame($event1->getEventId(), $eventDb->getEventId());
        $this->assertSame($event1->getAggregateId(), $eventDb->getAggregateId());
        $this->assertSame($event1->getOccurredOn()->getTimestamp(), $eventDb->getOccurredOn()->getTimestamp());
        $this->assertSame($event1->title, $eventDb->title);

        $version = $eventStore->getCurrentVersion($aggregateId);
        $this->assertSame(2, $version);
    }
}
