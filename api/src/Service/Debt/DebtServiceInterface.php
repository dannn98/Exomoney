<?php

namespace App\Service\Debt;

use App\DataObject\DebtDataObject;
use Symfony\Component\Security\Core\User\UserInterface;

interface DebtServiceInterface
{
    public function addDebt(DebtDataObject $dto, UserInterface $user): bool;
}