<?php

declare(strict_types=1);

namespace Auth\Application\Service\Auth;

use Auth\Application\Model\UserSecurity;

interface TokenService
{
    public function create(UserSecurity $user): string;
}
