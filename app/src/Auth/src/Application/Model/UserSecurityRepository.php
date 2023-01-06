<?php

namespace Auth\Application\Model;

use Auth\Application\Exception\NotFoundException;

interface UserSecurityRepository
{
    /**
     * @throws NotFoundException
     */
    public function findByUsername(string $username): UserSecurity;
}
