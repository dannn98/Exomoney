<?php

namespace App\Service\Debt;

use App\DataObject\DebtDataObject;
use App\Exception\ApiException;
use App\Service\Validator\ValidatorDTOInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DebtService implements DebtServiceInterface
{
    private ValidatorDTOInterface $validator;

    /**
     * DebtService construct
     *
     * @param ValidatorDTOInterface $validator
     */
    public function __construct(ValidatorDTOInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Add Debt
     *
     * @param DebtDataObject $dto
     * @param UserInterface $user
     *
     * @return bool
     * @throws ApiException
     */
    public function addDebt(DebtDataObject $dto, UserInterface $user): bool
    {
        $this->validator->validate($dto);
        dd($dto);


        return true;
    }
}