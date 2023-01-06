<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Projection\SecurityUser\Dbal;

use Auth\Domain\User\Event\UserWasRegistered;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Shared\Domain\Bus\Event\Event;
use Shared\Domain\Bus\Projection\Projection;

class UserWasRegisteredProjection implements Projection
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function listenTo(): array
    {
        return [
            UserWasRegistered::class,
        ];
    }

    /**
     * @param Event|UserWasRegistered $event
     *
     * @throws Exception
     */
    public function project($event): void
    {
        if (false === $event instanceof UserWasRegistered) {
            return;
        }
        $this->connection->insert(
            'security_user',
            [
                'id' => $event->getAggregateId(),
                'username' => $event->username,
                'password_hash' => $event->passwordHash,
            ]
        );
    }
}
