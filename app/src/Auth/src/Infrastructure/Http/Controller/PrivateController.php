<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Http\Controller;

use Auth\Infrastructure\Symfony\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PrivateController extends AbstractController
{
    #[Route('/private/', name: 'private_index', methods: 'GET')]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return new JsonResponse([
                'text' => "Hello, {$user->getUsername()}!",
            ]);
        } else {
            return new JsonResponse([
                'text' => "Hello, your id is {$user->getUserIdentifier()}!",
            ]);
        }
    }
}
