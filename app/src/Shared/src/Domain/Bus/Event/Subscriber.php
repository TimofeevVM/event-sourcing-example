<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

interface Subscriber
{
    /**
     * @return list<class-string<Event>>
     */
    public function subscribeTo(): array;
}
