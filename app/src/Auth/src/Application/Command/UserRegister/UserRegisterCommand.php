<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserRegister;

use Shared\Domain\Bus\Command\Command;

class UserRegisterCommand implements Command
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
