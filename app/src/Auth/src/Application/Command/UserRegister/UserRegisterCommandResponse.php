<?php

declare(strict_types=1);

namespace Auth\Application\Command\UserRegister;

use Shared\Domain\Bus\Command\CommandResponse;

class UserRegisterCommandResponse implements CommandResponse
{
    public function __construct(
        public readonly string $userId
    ) {
    }
}
