<?php

namespace App\Fixtures;

use App\Entity\Repayment;
use App\Entity\Team;
use App\Entity\User;
use App\Fixtures\Processors\UserProcessor;
use App\Repository\TeamRepository;
use App\Service\Repayment\RepaymentServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;

class FixturesService
{
    private EntityManagerInterface $em;
    private UserProcessor $userProcessor;
    private NativeLoader $loader;
    private RepaymentServiceInterface $repaymentService;
    private TeamRepository $teamRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserProcessor          $userProcessor,
        RepaymentServiceInterface $repaymentService,
        TeamRepository $teamRepository
    )
    {
        $this->em = $em;
        $this->userProcessor = $userProcessor;
        $this->repaymentService = $repaymentService;
        $this->teamRepository = $teamRepository;
        $this->loader = new NativeLoader();
    }

    public function generate(): void
    {
        $objectSet = $this->loader->loadFile(__DIR__ . '/fixtures.yml');

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof User) {
                $this->userProcessor->preProcess($object);
            }
            if ($object instanceof User || $object instanceof Team) {
                $this->em->persist($object);
            }
        }
        $this->em->flush();

        $max = 0;
        $teams = $this->teamRepository->findAll();
        foreach ($teams as $key => $team) {
            if($teams[$max]->getId() < $team->getId()) {
                $max = $key;
            }
        }

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof Repayment) {
                $this->em->persist($object);
                $this->em->flush();
//                $this->repaymentService->optimiseRepayments($teams[$max]);
            }
        }
    }
}