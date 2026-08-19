<?php

namespace App\Controller\Api;

use App\Entity\ManagementUsers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MeController extends AbstractController
{
    #[Route('/api/me', name: 'api_v3_me', methods: ['GET'])]
    public function __invoke(#[CurrentUser] ?ManagementUsers $user): JsonResponse
    {
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        return $this->json(
            [
            'id' => $user->getUserId(),
            'username' => $user->getUserName(),
            'email' => $user->getUserEmail(),
            'role' => $user->getUserRole(),
            'roles' => $user->getRoles(),
            'image' => $user->getUserImage(),
            ]
        );
    }
}
