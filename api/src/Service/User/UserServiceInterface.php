<?php

namespace App\Service\User;

use App\DataObject\UserDataObject;

interface UserServiceInterface
{
    public function createUser(UserDataObject $dto): bool;
}