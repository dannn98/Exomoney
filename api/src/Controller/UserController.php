<?php

namespace App\Controller;

use App\DataObject\UserDataObject;
use App\Exception\ApiException;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectService;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user', name: 'user.')]
class UserController extends AbstractController
{
    private DataObjectService $dataObjectService;
    private UserService $userService;

    /**
     * UserController
     *
     * @param DataObjectService $dataObjectService
     * @param UserService $userService
     */
    public function __construct(DataObjectService $dataObjectService, UserService $userService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->userService = $userService;
    }

    /**
     * Create User
     *
     * @param Request $request
     *
     * @return ApiResponse
     * @throws ApiException
     */
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request): ApiResponse
    {
        $userDTO = $this->dataObjectService->create($request, UserDataObject::class);

        $this->userService->createUser($userDTO);

        return new ApiResponse('Pomyślnie utworzono użytkownika', data: true, status: Response::HTTP_CREATED);
    }
}
