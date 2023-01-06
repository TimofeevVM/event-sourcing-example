<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

use Shared\Domain\Bus\Event\EventStream;

interface AggregateReconstructable
{
    public static function reconstruct(EventStream $eventStream): static;
}
