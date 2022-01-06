<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Fixtures\DataGenerator\DataGenerator;
use App\Fixtures\FixturesService;
use App\Http\ApiResponse;
use App\Repository\RepaymentRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\Repayment\RepaymentServiceInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/default', name: 'default.')]
class DefaultController extends AbstractController
{
    private FixturesService $fs;
    private UserRepository $userRepository;
    private TeamRepository $teamRepository;
    private RepaymentRepository $repaymentRepository;
    private RepaymentServiceInterface $repaymentService;
    private DataGenerator $dataGenerator;

    /**
     * @param FixturesService $fs
     * @param UserRepository $userRepository
     * @param TeamRepository $teamRepository
     * @param RepaymentRepository $repaymentRepository
     * @param RepaymentServiceInterface $repaymentService
     * @param DataGenerator $dataGenerator
     */
    public function __construct(
        FixturesService $fs,
        UserRepository $userRepository,
        TeamRepository $teamRepository,
        RepaymentRepository $repaymentRepository,
        RepaymentServiceInterface $repaymentService,
        DataGenerator $dataGenerator
    )
    {
        $this->fs = $fs;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->repaymentRepository = $repaymentRepository;
        $this->repaymentService = $repaymentService;
        $this->dataGenerator = $dataGenerator;
    }


    /**
     * @return JsonResponse
     */
    #[Route(name: 'default', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->fs->generate();

        return new ApiResponse('Wszystko ok');
    }

    /**
     * @param int $num
     *
     * @return JsonResponse
     * @throws ORMException
     * @throws ApiException
     */
    #[Route(path: '/insert/{num}', name: 'insert', methods: ['GET'])]
    public function insert(int $num): JsonResponse
    {
        if (!$this->dataGenerator->insert($num)) {
            throw new ApiException('Nie ma case\'u o podanym numerze', statusCode: Response::HTTP_NOT_FOUND);
        }
        return new ApiResponse('ok');
    }

    #[Route(path: '/optimise', name: 'optimise', methods: ['GET'])]
    public function optimise(): ApiResponse
    {
        $team = $this->teamRepository->find(29);
        $this->repaymentService->optimiseRepayments($team);

        return new ApiResponse('ok');
    }

    #[Route(path: '/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(): ApiResponse
    {
        $team = $this->teamRepository->find(12);
        $this->repaymentRepository->removeAllFromTeam($team);

        return new ApiResponse('ok');
    }
}
