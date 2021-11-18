<?php

namespace App\Service\TeamAccessCode;

use App\DataObject\TeamAccessCodeDataObject;
use Symfony\Component\Security\Core\User\UserInterface;

interface TeamAccessCodeServiceInterface
{
    public function addTeamAccessCode(TeamAccessCodeDataObject $dto, UserInterface $user): bool;
}