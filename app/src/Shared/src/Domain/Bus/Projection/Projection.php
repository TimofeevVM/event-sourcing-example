<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Projection;

use Shared\Domain\Bus\Event\Event;

interface Projection
{
    /**
     * @return list<class-string>
     */
    public function listenTo(): array;

    /**
     * @param Event $event
     */
    public function project(mixed $event): void;
}
