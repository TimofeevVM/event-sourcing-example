<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserChangePassword;

use Auth\Domain\User\Model\UserId;
use Auth\Domain\User\Model\UserPasswordHash;
use Auth\Domain\User\Model\UserRepositoryPersistence;
use Auth\Domain\User\PasswordEncryptor;
use Shared\Domain\Bus\Command\CommandHandler;

class UserChangePasswordCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepositoryPersistence $repositoryPersistence,
        private readonly PasswordEncryptor $passwordEncryptor
    ) {
    }

    public function __invoke(UserChangePasswordCommand $command): UserChangePasswordCommandResponse
    {
        $user = $this->repositoryPersistence->ofId(UserId::fromString($command->userId));

        $user->changePasswordHash(
            UserPasswordHash::fromString(
                $this->passwordEncryptor->encrypt($command->newPassword)
            )
        );

        $this->repositoryPersistence->persist($user);

        return new UserChangePasswordCommandResponse($user->id()->value());
    }
}
