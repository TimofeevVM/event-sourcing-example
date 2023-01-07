<?php

declare(strict_types=1);

namespace Shared\Domain\EventSourcing\EventStore;

use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\Bus\Event\EventStream;

interface EventStore
{
    public function append(EventStream $eventStream): self;

    public function getEventStream(AggregateId $aggregateId): EventStream;

    public function getCurrentVersion(AggregateId $aggregateId): int;

    public function getEventStreamFromVersion(AggregateId $aggregateId, int $version): EventStream;
}
