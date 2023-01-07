<?php

declare(strict_types=1);

namespace Shared\Domain\EventSourcing\Snapshot;

interface SnapshotRepository
{
    public function save(Snapshot $snapshot): void;

    public function get(string $aggregateId): Snapshot;
}
