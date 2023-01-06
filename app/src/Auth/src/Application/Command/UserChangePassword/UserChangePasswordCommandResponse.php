<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserChangePassword;

use Shared\Domain\Bus\Command\CommandResponse;

class UserChangePasswordCommandResponse implements CommandResponse
{
    public function __construct(
        public readonly string $userId
    ) {
    }
}
