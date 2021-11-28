<?php

namespace App\Service\Debt;

use App\DataObject\DebtDataObject;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

interface DebtServiceInterface
{
    public function addDebt(DebtDataObject $dto, UserInterface $user): bool;

    public function getDebtList(int $teamId, UserInterface $user): Collection;
}