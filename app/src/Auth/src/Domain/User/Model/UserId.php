<?php

declare(strict_types=1);

namespace Auth\Domain\User\Model;

use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\ValueObject\Uuid;

class UserId extends Uuid implements AggregateId
{
}
