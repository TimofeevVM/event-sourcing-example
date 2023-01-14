<?php

declare(strict_types=1);

namespace Auth\Domain\User\Event;

use JMS\Serializer\Annotation\Type;
use Shared\Domain\Bus\Event\Event;

class UserWasRegisteredV2 extends Event
{
    /**
     * @Type("array<string>")
     */
    public readonly array $roles;

    /**
     * @param string $aggregateId
     * @param string $username
     * @param string $email
     * @param string $passwordHash
     * @param array $roles
     */
    public function __construct(
        string $aggregateId,
        public readonly string $username,
        public readonly string $email,
        public readonly string $passwordHash,
        array $roles
    ) {
        $this->roles = $roles;
        parent::__construct($aggregateId);
    }

    public static function eventName(): string
    {
        return 'auth.domain.user.registered.v2';
    }
}
