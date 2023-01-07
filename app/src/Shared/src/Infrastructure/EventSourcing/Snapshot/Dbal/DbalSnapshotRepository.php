<?php

declare(strict_types=1);

namespace Shared\Infrastructure\EventSourcing\Snapshot\Dbal;

use Doctrine\DBAL\Connection;
use Shared\Domain\EventSourcing\Snapshot\Snapshot;
use Shared\Domain\EventSourcing\Snapshot\SnapshotNotFoundException;
use Shared\Domain\EventSourcing\Snapshot\SnapshotRepository;

class DbalSnapshotRepository implements SnapshotRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $tableName = 'event_store_snapshot',
    ) {
    }

    public function save(Snapshot $snapshot): void
    {
        $data = [
            'aggregate_id' => $snapshot->aggregateId,
            'version' => $snapshot->version,
            'aggregate_serialized' => $snapshot->aggregateSerialized,
        ];

        $snapshotExist = null;

        try {
            $snapshotExist = $this->get($snapshot->aggregateId);
        } catch (SnapshotNotFoundException) {
        }

        if ($snapshotExist) {
            $this->connection->update(
                $this->tableName,
                $data,
                ['aggregate_id' => $snapshotExist->aggregateId]
            );
        } else {
            $this->connection->insert($this->tableName, $data);
        }
    }

    public function get(string $aggregateId): Snapshot
    {
        $rows = $this->connection->fetchAllAssociative(
            'SELECT "version", "aggregate_id", "aggregate_serialized"'
            .' FROM '.$this->tableName
            .' WHERE "aggregate_id" = ?'
            .' LIMIT 1',
            [$aggregateId]
        );

        if (0 === count($rows)) {
            throw new SnapshotNotFoundException();
        }
        $snapshot = $rows[0];

        if (isset($snapshot['aggregate_id']) && isset($snapshot['version']) && isset($snapshot['aggregate_serialized'])) {
            return new Snapshot(
                (string) $snapshot['aggregate_id'],
                (int) $snapshot['version'],
                (string) $snapshot['aggregate_serialized'],
            );
        }

        throw new SnapshotNotFoundException();
    }
}
