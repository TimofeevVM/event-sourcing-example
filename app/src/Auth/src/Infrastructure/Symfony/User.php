<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Symfony;

use Auth\Application\Model\UserSecurity;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    public function __construct(
        protected readonly UserSecurity $securityUser
    ) {
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->securityUser->id;
    }

    public function getUsername(): string
    {
        return $this->securityUser->username;
    }
}
