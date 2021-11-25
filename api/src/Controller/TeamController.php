<?php

namespace App\Controller;

use App\DataObject\TeamAccessCodeDataObject;
use App\DataObject\TeamDataObject;
use App\Exception\ApiException;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectServiceInterface;
use App\Service\Team\TeamServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/team', name: 'team.')]
class TeamController extends AbstractController
{
    private DataObjectServiceInterface $dataObjectService;
    private TeamServiceInterface $teamService;

    /**
     * TeamController constructor
     *
     * @param DataObjectServiceInterface $dataObjectService
     * @param TeamServiceInterface $teamService
     */
    public function __construct(DataObjectServiceInterface $dataObjectService, TeamServiceInterface $teamService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->teamService = $teamService;
    }

    /**
     * Create Team
     *
     * @param Request $request
     * @return ApiResponse
     */
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request): ApiResponse
    {
        $teamDTO = $this->dataObjectService->create($request, TeamDataObject::class);

        $this->teamService->createTeam($teamDTO, $this->getUser());

        return new ApiResponse('Pomyślnie utworzono zespół', data: true, status: Response::HTTP_CREATED);
    }

    /**
     * Join to Team
     *
     * @param Request $request
     * @return ApiResponse
     */
    #[Route(path: '/join', name: 'join', methods: ['POST'])]
    public function join(Request $request): ApiResponse
    {
        $teamAccessCodeDTO = $this->dataObjectService->create($request, TeamAccessCodeDataObject::class);

        $this->teamService->joinTeam($teamAccessCodeDTO, $this->getUser());

        return new ApiResponse('Pomyślnie dodano użytkownika do zespołu', data: true, status: Response::HTTP_OK);
    }
}
