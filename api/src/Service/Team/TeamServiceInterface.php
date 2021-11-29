<?php

namespace App\Service\Team;

use App\DataObject\TeamAccessCodeDataObject;
use App\DataObject\TeamDataObject;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

interface TeamServiceInterface
{
    const DEFAULT_AVATAR = 'default.png';

    public function createTeam(TeamDataObject $dto, UserInterface $user): bool;

    public function joinTeam(TeamAccessCodeDataObject $dto, UserInterface $user): bool;

    public function getDebtList(int $teamId, UserInterface $user): Collection;

    public function getMemberList(int $teamId, UserInterface $user): Collection;
}