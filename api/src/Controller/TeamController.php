<?php

namespace App\Controller;

use App\DataObject\TeamDataObject;
use App\Exception\ApiException;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectService;
use App\Service\Team\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/team', name: 'team.')]
class TeamController extends AbstractController
{
    private DataObjectService $dataObjectService;
    private TeamService $teamService;

    /**
     * TeamController constructor
     *
     * @param DataObjectService $dataObjectService
     * @param TeamService $teamService
     */
    public function __construct(DataObjectService $dataObjectService, TeamService $teamService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->teamService = $teamService;
    }

    /**
     * Create Team
     *
     * @throws ApiException
     */
    #[Route(name: 'create', methods: ['POST'])]
    public function create(Request $request): ApiResponse
    {
        $teamDTO = $this->dataObjectService->create($request, TeamDataObject::class);

        $this->teamService->createTeam($teamDTO, $this->getUser());

        return new ApiResponse('Pomyślnie utworzono zespół', data: true, status: Response::HTTP_CREATED);
    }
}
