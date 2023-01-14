<?php

declare(strict_types=1);

namespace Auth\Domain\User\Event;

use Shared\Domain\EventSourcing\Upcasting\Upcaster;

class UserWasRegisteredV2Upcast implements Upcaster
{
    public function __invoke(UserWasRegistered $event): UserWasRegisteredV2
    {
        return new UserWasRegisteredV2(
            $event->getAggregateId(),
            $event->username,
            $event->email,
            $event->passwordHash,
            ['member'],
        );
    }
}
