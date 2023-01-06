<?php

declare(strict_types=1);

namespace Auth\Domain\User\Event;

use Shared\Domain\Bus\Event\Event;

class UserEmailWasChanged extends Event
{
    public function __construct(
        string $aggregateId,
        public readonly string $email,
    ) {
        parent::__construct($aggregateId);
    }

    public static function eventName(): string
    {
        return 'auth.domain.user.email.changed';
    }
}
