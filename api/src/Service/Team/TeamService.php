<?php

namespace App\Service\Team;

use App\DataObject\TeamDataObject;
use App\Entity\Team;
use App\Exception\ApiException;
use App\FileUploader\FileUploader;
use App\Repository\TeamRepository;
use App\Service\Validator\ValidatorDTO;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamService implements TeamServiceInterface
{
    private ValidatorDTO $validator;
    private FileUploader $fileUploader;
    private TeamRepository $teamRepository;

    /**
     * TeamService constructor
     *
     * @param ValidatorDTO $validator
     * @param FileUploader $fileUploader
     * @param TeamRepository $teamRepository
     */
    public function __construct(ValidatorDTO $validator, FileUploader $fileUploader, TeamRepository $teamRepository)
    {
        $this->validator = $validator;
        $this->fileUploader = $fileUploader;
        $this->teamRepository = $teamRepository;
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

        //TODO: Do stałej
        $avatarUrl = isset($dto->avatar_file) ? $this->fileUploader->upload($dto->avatar_file) : 'default.png';

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
}