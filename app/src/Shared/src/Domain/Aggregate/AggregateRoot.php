<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Shared\Domain\Bus\Event\Event;
use Shared\Domain\Bus\Event\EventStream;

abstract class AggregateRoot implements Aggregate, AggregateEventable, AggregateReconstructable
{
    private ?EventStream $events = null;
    private int $version = 0;

    final protected function __construct(
        protected AggregateId $id
    ) {
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function pullEvents(): EventStream
    {
        $events = $this->getEventStream();
        $this->events = new EventStream($this->id());

        return $events;
    }

    public static function reconstruct(EventStream $eventStream): static
    {
        $aggregate = new static($eventStream->aggregateId);

        foreach ($eventStream as $event) {
            $aggregate->apply($event);
        }

        return $aggregate;
    }

    protected function getEventStream(): EventStream
    {
        if ($this->events) {
            return $this->events;
        }

        $this->events = new EventStream($this->id());

        return $this->events;
    }

    protected function record(Event $events): void
    {
        $this->getEventStream()->append($events);
    }

    public function apply(Event $event): self
    {
        $classPart = explode('\\', get_class($event));
        $name = 'on'.array_pop($classPart);

        if (method_exists($this, $name)) {
            $this->{$name}($event);
        }

        return $this;
    }

    protected function recordAndApply(Event $event): void
    {
        $this->record($event);
        $this->apply($event);
    }
}
