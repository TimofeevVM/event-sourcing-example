<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserChangeEmail;

use Shared\Domain\Bus\Command\Command;

class UserChangeEmailCommand implements Command
{
    public function __construct(
        public readonly string $userId,
        public readonly string $newEmail,
    ) {
    }
}
