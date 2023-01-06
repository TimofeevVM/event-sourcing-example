<?php

declare(strict_types=1);

namespace Auth\Application\Query\GetAuthToken;

use Shared\Domain\Bus\Query\QueryResponse;

class GetAuthTokenResponse implements QueryResponse
{
    public function __construct(
        public readonly string $token
    ) {
    }
}
