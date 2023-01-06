<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserChangeEmail;

use Auth\Domain\User\Model\UserEmail;
use Auth\Domain\User\Model\UserId;
use Auth\Domain\User\Model\UserRepositoryPersistence;
use Shared\Domain\Bus\Command\CommandHandler;

class UserChangeEmailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepositoryPersistence $repositoryPersistence
    ) {
    }

    public function __invoke(UserChangeEmailCommand $command): UserChangeEmailCommandResponse
    {
        $user = $this->repositoryPersistence->ofId(UserId::fromString($command->userId));

        $user->changeEmail(UserEmail::fromString($command->newEmail));

        $this->repositoryPersistence->persist($user);

        return new UserChangeEmailCommandResponse($user->id()->value());
    }
}
