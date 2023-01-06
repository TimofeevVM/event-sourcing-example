<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

use Shared\Domain\ValueObject\Uuid;

abstract class Event
{
    protected readonly string $eventId;
    protected readonly \DateTime $occurredOn;
    protected int $version = 0;

    public function __construct(
        protected readonly string $aggregateId,
    ) {
        $this->eventId = Uuid::random()->value();
        $this->occurredOn = new \DateTime();
    }

    abstract public static function eventName(): string;

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getOccurredOn(): \DateTime
    {
        return $this->occurredOn;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
