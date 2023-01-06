<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Service\Application\Auth;

use Auth\Application\Model\UserSecurity;
use Auth\Application\Service\Auth\TokenService;
use Auth\Infrastructure\Symfony\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JwtTokenService implements TokenService
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager
    ) {
    }

    public function create(UserSecurity $user): string
    {
        return $this->jwtManager->create(new User($user));
    }
}
