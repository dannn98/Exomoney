<?php

namespace App\Service\Team;

use App\DataObject\TeamAccessCodeDataObject;
use App\DataObject\TeamDataObject;
use App\Entity\Team;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

interface TeamServiceInterface
{
    const DEFAULT_AVATAR = 'default.png';

    public function getTeam(int $teamId, UserInterface $user): Team;

    public function createTeam(TeamDataObject $dto, UserInterface $user): int;

    public function joinTeam(TeamAccessCodeDataObject $dto, UserInterface $user): int;

    public function getDebtList(int $teamId, UserInterface $user): Collection;

    public function getMemberList(int $teamId, UserInterface $user): Collection;

    public function getTeamAccessCode(int $teamId, UserInterface $user): ?string;
}