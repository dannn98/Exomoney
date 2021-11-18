<?php

namespace App\Service\Team;

use App\DataObject\TeamDataObject;
use Symfony\Component\Security\Core\User\UserInterface;

interface TeamServiceInterface
{
    const DEFAULT_AVATAR = 'default.png';

    public function createTeam(TeamDataObject $dto, UserInterface $user): bool;
}