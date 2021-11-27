<?php

namespace App\Service\Debt;

use App\DataObject\DebtDataObject;
use App\Entity\Debt;
use App\Exception\ApiException;
use App\Repository\DebtRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class DebtService implements DebtServiceInterface
{
    private ValidatorDTOInterface $validator;
    private DebtRepository $debtRepository;
    private TeamRepository $teamRepository;
    private UserRepository $userRepository;

    /**
     * DebtService construct
     *
     * @param ValidatorDTOInterface $validator
     * @param DebtRepository $debtRepository
     * @param TeamRepository $teamRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        ValidatorDTOInterface $validator,
        DebtRepository $debtRepository,
        TeamRepository $teamRepository,
        UserRepository $userRepository
    )
    {
        $this->validator = $validator;
        $this->debtRepository = $debtRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
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
            throw new ApiException('Użytkownik (kredytodawca) o podanym id nie należy do tego zespołu', statusCode: Response::HTTP_NOT_FOUND);
        }

        $debtCollection = array();

        foreach ($dto->debts as $debtorId => $value) {
            $debtor = $this->userRepository->findOneBy(['id' => $debtorId]);

            if ($debtor === null) {
                throw new ApiException('Użytkownik (kredytobiorca) o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
            }

            if (!$teamMembers->contains($debtor)) {
                throw new ApiException('Użytkownik (kredytobiorca) o podanym id nie należy do tego zespołu', statusCode: Response::HTTP_NOT_FOUND);
            }

            if ($debtor === $user) {
                throw new ApiException('Użytkownik (kredytodawca) nie może być kredytobiorcą', statusCode: Response::HTTP_CONFLICT);
            }

            $debt = new Debt();
            $debt->setTeam($team);
            $debt->setCreditor($user);
            $debt->setDebtor($debtor);
            $debt->setValue($value);

            $debtCollection[] = $debt;
        }

        $this->debtRepository->save($debtCollection);

        return true;
    }
}