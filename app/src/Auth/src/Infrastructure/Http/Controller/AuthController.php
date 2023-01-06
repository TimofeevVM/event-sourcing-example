<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Http\Controller;

use Auth\Application\Command\UserChangePassword\UserChangePasswordCommand;
use Auth\Application\Command\UserChangePassword\UserChangePasswordCommandHandler;
use Auth\Application\Command\UserRegister\UserRegisterCommand;
use Auth\Application\Command\UserRegister\UserRegisterCommandHandler;
use Auth\Application\Query\GetAuthToken\GetAuthTokenHandler;
use Auth\Application\Query\GetAuthToken\GetAuthTokenQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/registration/', name: 'auth_registration', methods: 'POST')]
    public function registration(Request $request, UserRegisterCommandHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $command = new UserRegisterCommand(
            $data['username'],
            $data['email'],
            $data['password']
        );

        return new JsonResponse([
            'user_id' => $handler($command)->userId,
        ]);
    }

    #[Route('/auth/login/', name: 'auth_login', methods: 'POST')]
    public function login(Request $request, GetAuthTokenHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $query = new GetAuthTokenQuery(
            $data['username'],
            $data['password'],
        );

        return new JsonResponse([
            'token' => $handler($query)->token,
        ]);
    }

    #[Route('/auth/change_password/', name: 'auth_change_password', methods: 'POST')]
    public function changePassword(Request $request, UserChangePasswordCommandHandler $handler): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $query = new UserChangePasswordCommand(
            $this->getUser()->getUserIdentifier(),
            $data['new_password'],
        );

        return new JsonResponse([
            'user_id' => $handler($query)->userId,
        ]);
    }
}
