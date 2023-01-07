<?php

declare(strict_types=1);

namespace Tests\Shared\Integration\Infrastructure\EventSourcing\EventStore\Dbal\Support;

use Shared\Domain\Bus\Event\Event;

class EventTested extends Event
{
    public function __construct(
        string $aggregateId,
        public readonly string $title,
    ) {
        parent::__construct($aggregateId);
    }

    public static function eventName(): string
    {
        return 'test.event.tested';
    }
}
