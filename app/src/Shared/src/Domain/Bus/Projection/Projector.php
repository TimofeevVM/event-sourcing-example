<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Projection;

use Shared\Domain\Bus\Event\EventStream;

interface Projector
{
    public function project(EventStream $eventStream): void;
}
