<?php

namespace Auth\Application\Model;

class UserSecurity
{
    public function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $passwordHash,
    ) {
    }
}
