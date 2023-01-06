<?php

declare(strict_types=1);

namespace Auth\Domain\User\Model;

use Auth\Domain\User\Exception\UserNotFoundException;
use Shared\Domain\Aggregate\AggregateId;

interface UserRepositoryPersistence
{
    /**
     * @throws UserNotFoundException
     */
    public function ofId(AggregateId $userId): User;

    public function persist(User $user): void;
}
