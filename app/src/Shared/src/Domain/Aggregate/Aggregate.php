<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

interface Aggregate
{
    public function id(): AggregateId;
}
