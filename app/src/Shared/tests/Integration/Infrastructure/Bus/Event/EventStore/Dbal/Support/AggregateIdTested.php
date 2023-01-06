<?php

declare(strict_types=1);

namespace Tests\Shared\Integration\Infrastructure\Bus\Event\EventStore\Dbal\Support;

use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\ValueObject\Uuid;

class AggregateIdTested extends Uuid implements AggregateId
{
}
