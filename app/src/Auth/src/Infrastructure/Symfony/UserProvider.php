<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Symfony;

use Auth\Application\Model\UserSecurityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(
        protected readonly UserSecurityRepository $repository
    ) {
    }

    public function refreshUser(UserInterface $user): void
    {
    }

    public function supportsClass(string $class): bool
    {
        return self::class === $class || is_subclass_of($class, self::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->repository->findByUsername($identifier);

        return new User($user);
    }
}
