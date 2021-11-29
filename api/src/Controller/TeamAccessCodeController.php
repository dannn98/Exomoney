<?php

namespace App\Controller;

use App\DataObject\TeamAccessCodeDataObject;
use App\Http\ApiResponse;
use App\Service\DataObject\DataObjectServiceInterface;
use App\Service\TeamAccessCode\TeamAccessCodeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/team-access-code', name: 'team-access-code.')]
class TeamAccessCodeController extends AbstractController
{
    private DataObjectServiceInterface $dataObjectService;
    private TeamAccessCodeServiceInterface $teamAccessCodeService;

    /**
     * TeamAccessCodeController constructor
     *
     * @param DataObjectServiceInterface $dataObjectService
     * @param TeamAccessCodeServiceInterface $teamAccessCodeService
     */
    public function __construct(DataObjectServiceInterface $dataObjectService, TeamAccessCodeServiceInterface $teamAccessCodeService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->teamAccessCodeService = $teamAccessCodeService;
    }

    #[Route(name: 'add', methods: ['POST'])]
    public function add(Request $request): ApiResponse
    {
        $teamAccessCodeDTO = $this->dataObjectService->create($request, TeamAccessCodeDataObject::class);

        $data['team_access_code'] = $this->teamAccessCodeService->addTeamAccessCode($teamAccessCodeDTO, $this->getUser());

        return new ApiResponse('Pomyślnie dodano Access code', data: $data, status: Response::HTTP_CREATED);
    }
}
