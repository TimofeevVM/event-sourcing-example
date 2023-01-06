<?php

declare(strict_types=1);

namespace Auth\Application\Query\GetAuthToken;

use Shared\Domain\Bus\Query\Query;

class GetAuthTokenQuery implements Query
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
    ) {
    }
}
