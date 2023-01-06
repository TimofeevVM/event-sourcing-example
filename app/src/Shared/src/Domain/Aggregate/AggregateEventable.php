<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Shared\Domain\Bus\Event\EventStream;

interface AggregateEventable
{
    public function pullEvents(): EventStream;
}
