<?php

namespace App\Controller;

use App\DataObject\UserDataObject;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectServiceInterface;
use App\Service\User\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/user', name: 'user.')]
class UserController extends AbstractController
{
    private SerializerInterface $serializer;
    private DataObjectServiceInterface $dataObjectService;
    private UserServiceInterface $userService;

    /**
     * UserController
     *
     * @param SerializerInterface $serializer
     * @param DataObjectServiceInterface $dataObjectService
     * @param UserServiceInterface $userService
     */
    public function __construct(
        SerializerInterface        $serializer,
        DataObjectServiceInterface $dataObjectService,
        UserServiceInterface       $userService
    )
    {
        $this->serializer = $serializer;
        $this->dataObjectService = $dataObjectService;
        $this->userService = $userService;
    }

    /**
     * Create User
     *
     * @param Request $request
     *
     * @return ApiResponse
     */
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request): ApiResponse
    {
        $userDTO = $this->dataObjectService->create($request, UserDataObject::class);

        $this->userService->createUser($userDTO);

        return new ApiResponse('Pomyślnie utworzono użytkownika', data: true, status: Response::HTTP_CREATED);
    }

    /**
     * Get team list
     *
     * @return ApiResponse
     */
    #[Route(path: '/teams', name: 'teams', methods: ['GET'])]
    public function getTeamList(): ApiResponse
    {
        $teamCollection = $this->userService->getTeamList($this->getUser());

        $data = $this->serializer->serialize($teamCollection, 'json', ['groups' => 'Get_team_list']);

        return new ApiResponse('Lista zespołów do których należy użytkownik', data: json_decode($data), status: Response::HTTP_OK);
    }
}
