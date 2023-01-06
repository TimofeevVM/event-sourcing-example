<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Service\Domain;

use Auth\Domain\User\PasswordEncryptor;

class Md5PasswordEncryptor implements PasswordEncryptor
{
    public function encrypt(string $password): string
    {
        return md5($password);
    }
}
