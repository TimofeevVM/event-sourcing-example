<?php

declare(strict_types=1);

namespace Auth\Domain\User\Event;

use Shared\Domain\Bus\Event\Event;

class UserWasRegistered extends Event
{
    public function __construct(
        string $aggregateId,
        public readonly string $username,
        public readonly string $email,
        public readonly string $passwordHash,
    ) {
        parent::__construct($aggregateId);
    }

    public static function eventName(): string
    {
        return 'auth.domain.user.registered';
    }
}
