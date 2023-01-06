<?php

declare(strict_types=1);

namespace Auth\Domain\User;

interface PasswordEncryptor
{
    public function encrypt(string $password): string;
}
