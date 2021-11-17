<?php

namespace App\Service\Team;

use App\DataObject\TeamDataObject;
use Symfony\Component\Security\Core\User\UserInterface;

interface TeamServiceInterface
{
    public function createTeam(TeamDataObject $dto, UserInterface $user): bool;
}