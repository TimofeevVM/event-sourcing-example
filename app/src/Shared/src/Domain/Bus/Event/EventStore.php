<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

use Shared\Domain\Aggregate\AggregateId;

interface EventStore
{
    public function append(EventStream $eventStream): self;

    public function getEventStream(AggregateId $aggregateId): EventStream;

    public function getCurrentVersion(AggregateId $aggregateId): int;
}
