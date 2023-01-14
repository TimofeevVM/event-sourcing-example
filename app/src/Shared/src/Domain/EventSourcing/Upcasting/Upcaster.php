<?php

declare(strict_types=1);

namespace Shared\Domain\EventSourcing\Upcasting;

use Shared\Domain\Bus\Event\Event;

/**
 * @method __invoke(Event $event): Event
 */
interface Upcaster
{
}
