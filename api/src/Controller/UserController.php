<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user', name: 'user.')]
class UserController extends AbstractController
{
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($this->userService->createUser($data)) {
            return new JsonResponse(['message' => 'Dodano użytkownika'], Response::HTTP_CREATED, []);
        }

        return new JsonResponse(['message' => 'Wystąpił błąd'], Response::HTTP_CONFLICT, []);
    }
}
