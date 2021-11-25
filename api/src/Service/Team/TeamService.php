<?php

namespace App\Service\Team;

use App\DataObject\TeamAccessCodeDataObject;
use App\DataObject\TeamDataObject;
use App\Entity\Team;
use App\Entity\TeamAccessCode;
use App\Exception\ApiException;
use App\FileUploader\FileUploader;
use App\Repository\TeamAccessCodeRepository;
use App\Repository\TeamRepository;
use App\Service\Validator\ValidatorDTOInterface;
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
        $this->validator->validate($dto, [$dto::JOIN]);

        try {
            $teamAccessCode = $this->teamAccessCodeRepository->findOneBy(['code' => $dto->code]);

            if($teamAccessCode === null) {
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
}