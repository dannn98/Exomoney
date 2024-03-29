<?php

namespace App\Service\TeamAccessCode;

use App\DataObject\TeamAccessCodeDataObject;
use App\Entity\TeamAccessCode;
use App\EntityManager\Transaction;
use App\Exception\ApiException;
use App\Repository\TeamAccessCodeRepository;
use App\Repository\TeamRepository;
use App\Service\RandomCodeGenerator\RandomCodeGeneratorService;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamAccessCodeService implements TeamAccessCodeServiceInterface
{
    private ValidatorDTOInterface $validator;
    private RandomCodeGeneratorService $randomCodeGeneratorService;
    private TeamAccessCodeRepository $teamAccessCodeRepository;
    private TeamRepository $teamRepository;

    /**
     * TeamAccessCodeService constructor
     *
     * @param ValidatorDTOInterface $validator
     * @param RandomCodeGeneratorService $randomCodeGeneratorService
     * @param TeamAccessCodeRepository $teamAccessCodeRepository
     * @param TeamRepository $teamRepository
     */
    public function __construct(
        ValidatorDTOInterface $validator,
        RandomCodeGeneratorService $randomCodeGeneratorService,
        TeamAccessCodeRepository $teamAccessCodeRepository,
        TeamRepository $teamRepository
    )
    {
        $this->validator = $validator;
        $this->randomCodeGeneratorService = $randomCodeGeneratorService;
        $this->teamAccessCodeRepository = $teamAccessCodeRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Get TeamAccessCode
     *
     * @param int $teamId
     * @param UserInterface $user
     *
     * @return string
     * @throws ApiException
     */
    public function getTeamAccessCode(int $teamId, UserInterface $user): string
    {
        $team = $this->teamRepository->findOneBy(['id' => $teamId]);

        if ($team === null) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if ($team->getOwner()->getId() != $user->getId()) {
            throw new ApiException('Użytkownik nie jest właścicielem', statusCode: Response::HTTP_FORBIDDEN);
        }

        return isset($team->getTeamAccessCodes()[0]) ? $team->getTeamAccessCodes()[0]->getCode() : '';
    }

    /**
     * Add TeamAccessCode
     *
     * @throws ApiException
     * @throws Exception
     */
    public function addTeamAccessCode(TeamAccessCodeDataObject $dto, UserInterface $user): string
    {
        $this->validator->validate($dto);

        $team = $this->teamRepository->find($dto->team_id);

        if ($team === null) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if ($team->getOwner()->getId() != $user->getId()) {
            throw new ApiException('Użytkownik nie jest właścicielem', statusCode: Response::HTTP_FORBIDDEN);
        }

        $code = $this->randomCodeGeneratorService->getCode();

        $teamAccessCode = new TeamAccessCode();
        $teamAccessCode->setTeam($team);
        $teamAccessCode->setCode($code);
        $teamAccessCode->setNumberOfUses($dto->number_of_uses ?? null);
        $teamAccessCode->setExpireTime(isset($dto->expire_time) ? new \DateTime($dto->expire_time) : null);

        try {
            Transaction::beginTransaction();
            $this->teamAccessCodeRepository->delete($team->getTeamAccessCodes());
            $this->teamAccessCodeRepository->save($teamAccessCode);
            Transaction::commit();
        } catch (OptimisticLockException | ORMException | Exception $e) {
            Transaction::rollback();
            throw $e;
        }

        return $code;
    }
}