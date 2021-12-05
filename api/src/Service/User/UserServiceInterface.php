<?php

namespace App\Service\User;

use App\DataObject\UserDataObject;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserServiceInterface
{
    public function createUser(UserDataObject $dto): bool;

    public function getTeamList(UserInterface $user): Collection;

    public function getRepaymentList(int $teamId, UserInterface $user): array;
}