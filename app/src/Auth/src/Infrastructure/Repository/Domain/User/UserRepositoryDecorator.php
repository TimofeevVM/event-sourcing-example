<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repository\Domain\User;

use Auth\Domain\User\Model\User;
use Auth\Domain\User\Model\UserRepositoryPersistence;
use Shared\Domain\Aggregate\AggregateId;

class UserRepositoryDecorator implements UserRepositoryPersistence
{
    public function __construct(
        protected readonly UserRepositoryPersistence $repository,
    ) {
    }

    public function ofId(AggregateId $userId): User
    {
        return $this->repository->ofId($userId);
    }

    public function persist(User $user): void
    {
        $this->repository->persist($user);
    }
}
