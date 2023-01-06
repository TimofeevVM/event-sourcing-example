<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserRegister;

use Auth\Domain\User\Model\User;
use Auth\Domain\User\Model\UserEmail;
use Auth\Domain\User\Model\Username;
use Auth\Domain\User\Model\UserPasswordHash;
use Auth\Domain\User\Model\UserRepositoryPersistence;
use Auth\Domain\User\PasswordEncryptor;
use Shared\Domain\Bus\Command\CommandHandler;

class UserRegisterCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepositoryPersistence $repositoryPersistence,
        private readonly PasswordEncryptor $passwordEncryptor,
    ) {
    }

    public function __invoke(UserRegisterCommand $command): UserRegisterCommandResponse
    {
        $user = User::register(
            Username::fromString($command->username),
            UserEmail::fromString($command->email),
            UserPasswordHash::fromString($this->passwordEncryptor->encrypt($command->password))
        );

        $this->repositoryPersistence->persist($user);

        return new UserRegisterCommandResponse($user->id()->value());
    }
}
