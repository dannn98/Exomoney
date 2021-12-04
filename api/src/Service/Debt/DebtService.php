<?php

namespace App\Service\Debt;

use App\DataObject\DebtDataObject;
use App\Entity\Debt;
use App\Entity\Repayment;
use App\EntityManager\Transaction;
use App\Exception\ApiException;
use App\Repository\DebtRepository;
use App\Repository\RepaymentRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class DebtService implements DebtServiceInterface
{
    private ValidatorDTOInterface $validator;
    private DebtRepository $debtRepository;
    private TeamRepository $teamRepository;
    private UserRepository $userRepository;
    private RepaymentRepository $repaymentRepository;

    /**
     * DebtService construct
     *
     * @param ValidatorDTOInterface $validator
     * @param DebtRepository $debtRepository
     * @param TeamRepository $teamRepository
     * @param UserRepository $userRepository
     * @param RepaymentRepository $repaymentRepository
     */
    public function __construct(
        ValidatorDTOInterface $validator,
        DebtRepository $debtRepository,
        TeamRepository $teamRepository,
        UserRepository $userRepository,
        RepaymentRepository $repaymentRepository
    )
    {
        $this->validator = $validator;
        $this->debtRepository = $debtRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->repaymentRepository = $repaymentRepository;
    }

    /**
     * Add Debt
     *
     * @param DebtDataObject $dto
     * @param UserInterface $user
     *
     * @return bool
     * @throws ApiException|ORMException
     */
    public function addDebt(DebtDataObject $dto, UserInterface $user): bool
    {
        $this->validator->validate($dto);

        $team = $this->teamRepository->findOneBy(['id' => $dto->team_id]);

        if ($team === null) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        $teamMembers = $team->getUsers();

        if (!$teamMembers->contains($user)) {
            throw new ApiException('Użytkownik nie należy do podanego zespołu', statusCode: Response::HTTP_NOT_FOUND);
        }

        $debtors = $this->userRepository->findBy(['id' => array_keys($dto->debts)]);
        $repayments = $this->repaymentRepository->findBy([
            'team' => $team,
            'debtor' => $debtors,
            'creditor' => $user
        ]);

        if (count($debtors) != count($dto->debts)) {
            throw new ApiException('Użytkownik (kredytobiorca) o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        $debtCollection = array();
        $repaymentCollection = array();

        foreach ($debtors as $debtor) {
            if (!$teamMembers->contains($debtor)) {
                throw new ApiException('Użytkownik (kredytobiorca) o podanym id nie należy do podanego zespołu', statusCode: Response::HTTP_NOT_FOUND);
            }

            if ($debtor === $user) {
                throw new ApiException('Użytkownik (kredytodawca) nie może być kredytobiorcą', statusCode: Response::HTTP_CONFLICT);
            }

            $debt = new Debt();
            $debt->setTitle($dto->title);
            $debt->setTeam($team);
            $debt->setCreditor($user);
            $debt->setDebtor($debtor);
            $debt->setValue($dto->debts[$debtor->getId()]);

            $repayment = null;

            foreach ($repayments as $element) {
                if ($element->getDebtor() === $debtor) {
                    $repayment = $element;
                    break;
                }
            }

            if (!$repayment) {
                $repayment = new Repayment();
                $repayment->setTeam($team);
                $repayment->setDebtor($debtor);
                $repayment->setCreditor($user);
            }

            $repayment->getValue() ?
                $repayment->setValue(bcadd($repayment->getValue(), $dto->debts[$debtor->getId()], 2)) :
                $repayment->setValue($dto->debts[$debtor->getId()]);

            $debtCollection[] = $debt;
            $repaymentCollection[] = $repayment;
        }

        try {
            Transaction::beginTransaction();
            $this->debtRepository->saveCollection($debtCollection);
            $this->repaymentRepository->saveCollection($repaymentCollection);
            Transaction::commit();
        } catch (OptimisticLockException | ORMException | Exception $e) {
            Transaction::rollback();
            throw $e;
        }

        return true;
    }
}