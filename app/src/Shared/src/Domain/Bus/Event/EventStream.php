<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

use Shared\Domain\Aggregate\AggregateId;

/**
 * @template-implements \Iterator<int, Event>
 */
final class EventStream implements \Iterator, \Countable
{
    /**
     * @var Event[]
     */
    private array $events = [];

    public function __construct(
        public readonly AggregateId $aggregateId
    ) {
    }

    public function append(Event $event): self
    {
        if ($this->aggregateId->value() !== $event->getAggregateId()) {
            throw new AggregateIdDoesMatchException();
        }

        $this->events[] = $event;

        return $this;
    }

    public function current(): Event
    {
        return current($this->events);
    }

    public function next(): void
    {
        next($this->events);
    }

    public function key(): int|string|null
    {
        return key($this->events);
    }

    public function valid(): bool
    {
        return null !== key($this->events);
    }

    public function rewind(): void
    {
        reset($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }
}
