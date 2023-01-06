<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Projection\SecurityUser\Dbal;

use Auth\Domain\User\Event\UserPasswordHashWasChanged;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shared\Domain\Bus\Event\Event;
use Shared\Domain\Bus\Projection\Projection;

class UserWasPasswordHashChangedProjection implements Projection
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function listenTo(): array
    {
        return [
            UserPasswordHashWasChanged::class,
        ];
    }

    /**
     * @param Event|UserPasswordHashWasChanged $event
     *
     * @throws Exception
     */
    public function project($event): void
    {
        if (false === $event instanceof UserPasswordHashWasChanged) {
            return;
        }

        $this->connection->update('security_user',
            [
                'password_hash' => $event->passwordHash,
            ],
            [
                'id' => $event->getAggregateId(),
            ]
        );
    }
}
