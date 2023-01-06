<?php

declare(strict_types=1);

namespace Auth\Domain\User\Exception;

use Auth\Domain\Exception\AuthDomainException;
use Auth\Domain\User\Model\UserId;

class UserNotFoundException extends AuthDomainException
{
    public function __construct(UserId $userId)
    {
        parent::__construct(
            sprintf(
                'User with id %s not found',
                $userId->value(),
            )
        );
    }
}
