<?php

namespace App\Controller;

use App\DataObject\TeamAccessCodeDataObject;
use App\DataObject\TeamDataObject;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectServiceInterface;
use App\Service\Team\TeamServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/team', name: 'team.')]
class TeamController extends AbstractController
{
    private SerializerInterface $serializer;
    private DataObjectServiceInterface $dataObjectService;
    private TeamServiceInterface $teamService;

    /**
     * TeamController constructor
     *
     * @param SerializerInterface $serializer
     * @param DataObjectServiceInterface $dataObjectService
     * @param TeamServiceInterface $teamService
     */
    public function __construct(SerializerInterface $serializer, DataObjectServiceInterface $dataObjectService, TeamServiceInterface $teamService)
    {
        $this->serializer = $serializer;
        $this->dataObjectService = $dataObjectService;
        $this->teamService = $teamService;
    }

    /**
     * Create Team
     *
     * @param Request $request
     *
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
     *
     * @return ApiResponse
     */
    #[Route(path: '/join', name: 'join', methods: ['POST'])]
    public function join(Request $request): ApiResponse
    {
        $teamAccessCodeDTO = $this->dataObjectService->create($request, TeamAccessCodeDataObject::class);

        $this->teamService->joinTeam($teamAccessCodeDTO, $this->getUser());

        return new ApiResponse('Pomyślnie dodano użytkownika do zespołu', data: true, status: Response::HTTP_OK);
    }

    /**
     * Get debt list
     *
     * @param int $teamId
     *
     * @return ApiResponse
     */
    #[Route(path: '/{teamId}/debts', name: 'debts', methods: ['GET'])]
    public function getDebtList(int $teamId): ApiResponse
    {
        $debtCollection = $this->teamService->getDebtList($teamId, $this->getUser());

        $data = $this->serializer->serialize($debtCollection, 'json', ['groups' => 'Get_debt_list']);

        return new ApiResponse('Lista długów dla zespołu o id ' . $teamId, data: json_decode($data), status: Response::HTTP_OK);
    }

    /**
     * Get member list
     *
     * @param int $teamId
     *
     * @return ApiResponse
     */
    #[Route(path: '/{teamId}/members', name: 'members', methods: ['GET'])]
    public function getMemberList(int $teamId): ApiResponse
    {
        $memberCollection = $this->teamService->getMemberList($teamId, $this->getUser());

        $data = $this->serializer->serialize($memberCollection, 'json', ['groups' => 'Get_member_list']);

        return new ApiResponse('Lista członków zespołu o id ' . $teamId, data: json_decode($data), status: Response::HTTP_OK);
    }

    /**
     * Get team access code
     *
     * @param int $teamId
     *
     * @return ApiResponse
     */
    #[Route(path: '/{teamId}/team-access-code', name: 'team-access-code', methods: ['GET'])]
    public function getTeamAccessCode(int $teamId): ApiResponse
    {
        $data['team_access_code'] = $this->teamService->getTeamAccessCode($teamId, $this->getUser());

        return new ApiResponse('Kod dostępowy dla zespołu o id ' . $teamId, data: $data, status: Response::HTTP_OK);
    }
}
