<?php

declare(strict_types=1);

namespace Auth\Application\Query\GetAuthToken;

use Auth\Application\Exception\GenerateTokenException;
use Auth\Application\Exception\NotFoundException;
use Auth\Application\Model\UserSecurityRepository;
use Auth\Application\Service\Auth\TokenService;
use Auth\Domain\User\PasswordEncryptor;
use Shared\Domain\Bus\Query\QueryHandler;

class GetAuthTokenHandler implements QueryHandler
{
    public function __construct(
        private readonly TokenService $tokenService,
        private readonly UserSecurityRepository $repository,
        private readonly PasswordEncryptor $passwordEncryptor,
    ) {
    }

    public function __invoke(GetAuthTokenQuery $query): GetAuthTokenResponse
    {
        try {
            $user = $this->repository->findByUsername($query->username);
        } catch (NotFoundException) {
            throw new GenerateTokenException('Check credentials');
        }

        $password = $this->passwordEncryptor->encrypt($query->password);
        if ($user->passwordHash === $password) {
            return new GetAuthTokenResponse(
                $this->tokenService->create($user)
            );
        }

        throw new GenerateTokenException('Check credentials');
    }
}
