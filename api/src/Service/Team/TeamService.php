<?php

namespace App\Service\Team;

use App\DataObject\TeamAccessCodeDataObject;
use App\DataObject\TeamDataObject;
use App\Entity\Team;
use App\Exception\ApiException;
use App\FileUploader\FileUploader;
use App\Repository\TeamAccessCodeRepository;
use App\Repository\TeamRepository;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamService implements TeamServiceInterface
{
    private ValidatorDTOInterface $validator;
    private FileUploader $fileUploader;
    private TeamRepository $teamRepository;
    private TeamAccessCodeRepository $teamAccessCodeRepository;

    /**
     * TeamService constructor
     *
     * @param ValidatorDTOInterface $validator
     * @param FileUploader $fileUploader
     * @param TeamRepository $teamRepository
     * @param TeamAccessCodeRepository $teamAccessCodeRepository
     */
    public function __construct(
        ValidatorDTOInterface    $validator,
        FileUploader             $fileUploader,
        TeamRepository           $teamRepository,
        TeamAccessCodeRepository $teamAccessCodeRepository)
    {
        $this->validator = $validator;
        $this->fileUploader = $fileUploader;
        $this->teamRepository = $teamRepository;
        $this->teamAccessCodeRepository = $teamAccessCodeRepository;
    }

    /**
     * Create Team
     *
     * @param TeamDataObject $dto
     * @param UserInterface $user
     *
     * @return bool
     * @throws ApiException
     */
    public function createTeam(TeamDataObject $dto, UserInterface $user): bool
    {
        $this->validator->validate($dto);

        $avatarUrl = isset($dto->avatar_file) ? $this->fileUploader->upload($dto->avatar_file) : self::DEFAULT_AVATAR;

        $team = new Team();
        $team->setOwner($user);
        $team->setName($dto->name);
        $team->setAvatarUrl($avatarUrl);
        $team->addUser($user);

        try {
            $this->teamRepository->save($team);
        } catch (OptimisticLockException | ORMException $e) {

        }

        return true;
    }

    /**
     * Join to Team
     *
     * @param TeamAccessCodeDataObject $dto
     * @param UserInterface $user
     *
     * @return bool
     * @throws ApiException
     */
    public function joinTeam(TeamAccessCodeDataObject $dto, UserInterface $user): bool
    {
        $this->validator->validate($dto, [$dto::JOIN_GROUP]);

        try {
            $teamAccessCode = $this->teamAccessCodeRepository->findOneBy(['code' => $dto->code]);

            if ($teamAccessCode === null) {
                throw new ApiException('Podano błędny access code', statusCode: Response::HTTP_NOT_FOUND);
            }
            //TODO: Do obsłużenia
//            if($teamAccessCode->getNumberOfUses() != null) {
//                if($teamAccessCode->getNumberOfUses() == 0) {
//                    throw new ApiException('Podany access code wygasł', statusCode: Response::HTTP_BAD_REQUEST);
//                }
//            }
//
//            if($teamAccessCode->getExpireTime() != null) {
//                if($teamAccessCode->getExpireTime()->diff(new \DateTime()) < 0) {
//                    throw new ApiException('Podany access code wygasł', statusCode: Response::HTTP_BAD_REQUEST);
//                }
//            }

            $team = $teamAccessCode->getTeam();
            $team->addUser($user);

            $this->teamRepository->save($team);
        } catch (OptimisticLockException | ORMException $e) {

        }

        return true;
    }

    /**
     * Get debt list for team
     *
     * @param int $teamId
     * @param UserInterface $user
     *
     * @return Collection
     * @throws ApiException
     */
    public function getDebtList(int $teamId, UserInterface $user): Collection
    {
        $team = $this->teamRepository->findOneBy(['id' => $teamId]);

        if ($team === null) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if (!$team->getUsers()->contains($user)) {
            throw new ApiException('Użytkownik nie należy do podanego zespołu', statusCode: Response::HTTP_NOT_FOUND);
        }
        //TODO: Do refaktoryzacji
        return $team->getDebts();
    }

    /**
     * Get member list
     *
     * @param int $teamId
     * @param UserInterface $user
     *
     * @return Collection
     * @throws ApiException
     */
    public function getMemberList(int $teamId, UserInterface $user): Collection
    {
        $team = $this->teamRepository->findOneBy(['id' => $teamId]);

        if ($team === null) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if (!$team->getUsers()->contains($user)) {
            throw new ApiException('Użytkownik nie należy do podanego zespołu', statusCode: Response::HTTP_NOT_FOUND);
        }

        return $team->getUsers();
    }

    /**
     * Get team access code
     *
     * @param int $teamId
     * @param UserInterface $user
     *
     * @return string|null
     * @throws ApiException
     */
    public function getTeamAccessCode(int $teamId, UserInterface $user): ?string
    {
        $team = $this->teamRepository->findOneBy(['id' => $teamId]);

        if ($team === null) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if ($team->getOwner() !== $user) {
            throw new ApiException('Użytkownik nie jest właścicielem zespołu', statusCode: Response::HTTP_CONFLICT); //TODO: 403
        }

        return $team->getTeamAccessCodes()[0]->getCode();
    }
}