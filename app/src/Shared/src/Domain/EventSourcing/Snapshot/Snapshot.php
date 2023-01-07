<?php

declare(strict_types=1);

namespace Shared\Domain\EventSourcing\Snapshot;

class Snapshot
{
    public function __construct(
        public readonly string $aggregateId,
        public readonly int $version,
        public readonly string $aggregateSerialized,
    ) {
    }
}
