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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
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
        ValidatorDTOInterface $validator,
        FileUploader $fileUploader,
        TeamRepository $teamRepository,
        TeamAccessCodeRepository $teamAccessCodeRepository)
    {
        $this->validator = $validator;
        $this->fileUploader = $fileUploader;
        $this->teamRepository = $teamRepository;
        $this->teamAccessCodeRepository = $teamAccessCodeRepository;
    }

    /**
     * @param int $teamId
     *
     * @return Team
     * @throws ApiException
     */
    public function getTeam(int $teamId, UserInterface $user): Team
    {
        $team = $this->teamRepository->findOneBy(['id' => $teamId]);

        if (!$team) {
            throw new ApiException('Zespół o podanym id nie istnieje', statusCode: Response::HTTP_NOT_FOUND);
        }

        if (!$team->getUsers()->contains($user)) {
            throw new ApiException('Użytkownik nie należy do podanego zespołu', statusCode: Response::HTTP_NOT_FOUND);
        }

        $team->setAvatarUrl(
            Request::createFromGlobals()->getSchemeAndHttpHost() .
            $this->fileUploader->getTargetUrl() .
            $team->getAvatarUrl()
        );

        return $team;
    }

    /**
     * Create Team
     *
     * @param TeamDataObject $dto
     * @param UserInterface $user
     *
     * @return int
     * @throws ApiException
     */
    public function createTeam(TeamDataObject $dto, UserInterface $user): int
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

        return $team->getId();
    }

    /**
     * Join to Team
     *
     * @param TeamAccessCodeDataObject $dto
     * @param UserInterface $user
     *
     * @return int
     * @throws ApiException
     */
    public function joinTeam(TeamAccessCodeDataObject $dto, UserInterface $user): int
    {
        $team = null;
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

        return $team->getId();
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

        $members = $team->getUsers();
        $members->removeElement($user);

        return new ArrayCollection($members->getValues());
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
            throw new ApiException('Użytkownik nie jest właścicielem zespołu', statusCode: Response::HTTP_FORBIDDEN);
        }

        return $team->getTeamAccessCodes()[0] ? $team->getTeamAccessCodes()[0]->getCode() : null;
    }
}