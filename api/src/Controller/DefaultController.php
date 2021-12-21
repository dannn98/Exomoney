<?php

namespace App\Controller;

use App\Entity\Repayment;
use App\Exception\ApiException;
use App\Fixtures\FixturesService;
use App\Http\ApiResponse;
use App\Repository\RepaymentRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
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

    /**
     * @param FixturesService $fs
     * @param UserRepository $userRepository
     * @param TeamRepository $teamRepository
     * @param RepaymentRepository $repaymentRepository
     */
    public function __construct(
        FixturesService $fs,
        UserRepository $userRepository,
        TeamRepository $teamRepository,
        RepaymentRepository $repaymentRepository
    )
    {
        $this->fs = $fs;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->repaymentRepository = $repaymentRepository;
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
     * @return JsonResponse
     */
    #[Route(path: '/insert', name: 'insert', methods: ['GET'])]
    public function insert(): JsonResponse
    {
        $team = $this->teamRepository->find(12);
        $res = $this->userRepository->findBy(['nickname' => ['Gabe', 'Bob', 'David', 'Fred', 'Charlie', 'Ema']]);
        $users = array();
        foreach ($res as $user) {
            $users[$user->getNickname()] = $user;
        }

        $repayments = array();

        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Gabe'])->setCreditor($users['Bob'])->setValue(30);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Gabe'])->setCreditor($users['David'])->setValue(10);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Fred'])->setCreditor($users['Bob'])->setValue(10);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Fred'])->setCreditor($users['Charlie'])->setValue(30);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Fred'])->setCreditor($users['David'])->setValue(10);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Fred'])->setCreditor($users['Ema'])->setValue(10);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Bob'])->setCreditor($users['Charlie'])->setValue(40);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['Charlie'])->setCreditor($users['David'])->setValue(20);
        $repayment = new Repayment();
        $repayments[] = $repayment->setTeam($team)->setDebtor($users['David'])->setCreditor($users['Ema'])->setValue(55);

        $this->repaymentRepository->saveCollection($repayments);

        return new ApiResponse('Dodane');
    }
}
