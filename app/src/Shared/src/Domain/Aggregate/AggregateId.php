<?php

declare(strict_types=1);

namespace Shared\Domain\Aggregate;

interface AggregateId
{
    public function value(): string;
}
