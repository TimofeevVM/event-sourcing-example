<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserChangePassword;

use Shared\Domain\Bus\Command\Command;

class UserChangePasswordCommand implements Command
{
    public function __construct(
        public readonly string $userId,
        public readonly string $newPassword,
    ) {
    }
}
