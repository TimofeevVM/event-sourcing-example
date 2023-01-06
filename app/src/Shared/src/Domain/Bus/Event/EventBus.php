<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

interface EventBus
{
    public function publish(EventStream $eventStream): void;
}
