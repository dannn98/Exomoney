<?php


namespace App\Fixtures\DataGenerator;


use App\Entity\Repayment;
use App\Repository\RepaymentRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\ORMException;

class DataGenerator
{
    const TEAM_ID = 12;
    const USERS_ID = ['21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35'];

    private UserRepository $userRepository;
    private TeamRepository $teamRepository;
    private RepaymentRepository $repaymentRepository;

    /**
     * DataGenerator constructor.
     *
     * @param UserRepository $userRepository
     * @param TeamRepository $teamRepository
     * @param RepaymentRepository $repaymentRepository
     */
    public function __construct(
        UserRepository $userRepository,
        TeamRepository $teamRepository,
        RepaymentRepository $repaymentRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->repaymentRepository = $repaymentRepository;
    }

    /**
     * @throws ORMException
     */
    public function insert(int $num): bool
    {
        $users = array();
        $repaymentsCollection = array();
        $team = $this->teamRepository->find(self::TEAM_ID);
        $result = $this->userRepository->findBy(['id' => self::USERS_ID]);
        foreach ($result as $user) {
            $users[$user->getId()] = $user;
        }

        switch ($num) {
            case 1:
                $leans = $this->chainCase();
                break;
            case 2:
                $leans = $this->easyCase();
                break;
            default:
                return false;
        }

        /** @var array $lean */
        foreach ($leans as $lean) {
            $repayment = new Repayment();
            $repayment->setTeam($team);
            $repayment->setDebtor($users[$lean['d']]);
            $repayment->setCreditor($users[$lean['c']]);
            $repayment->setValue($lean['v']);
            $repaymentsCollection[] = $repayment;
        }

        $this->repaymentRepository->saveCollection($repaymentsCollection);

        return true;
    }

    private function chainCase(): array
    {
        $leans[] = ['d' => '21', 'c' => '24', 'v' => '10.00'];
        $leans[] = ['d' => '24', 'c' => '22', 'v' => '10.00'];
        $leans[] = ['d' => '27', 'c' => '23', 'v' => '10.00'];
        $leans[] = ['d' => '22', 'c' => '27', 'v' => '10.00'];
        $leans[] = ['d' => '23', 'c' => '33', 'v' => '10.00'];
        return $leans;
    }

    private function easyCase(): array
    {
        $leans[] = ['d' => '21', 'c' => '22', 'v' => '30.00'];
        $leans[] = ['d' => '21', 'c' => '23', 'v' => '10.00'];
        $leans[] = ['d' => '24', 'c' => '22', 'v' => '10.00'];
        $leans[] = ['d' => '24', 'c' => '25', 'v' => '30.00'];
        $leans[] = ['d' => '24', 'c' => '23', 'v' => '10.00'];
        $leans[] = ['d' => '24', 'c' => '26', 'v' => '10.00'];
        $leans[] = ['d' => '22', 'c' => '25', 'v' => '40.00'];
        $leans[] = ['d' => '25', 'c' => '23', 'v' => '20.00'];
        $leans[] = ['d' => '23', 'c' => '26', 'v' => '50.00'];
        return $leans;
    }
}