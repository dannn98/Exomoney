<?php

namespace App\Service\Validator;

use App\DataObject\DataObjectAbstract;
use App\Exception\ApiException;

interface ValidatorDTOInterface
{
    /**
     * Validate DTO object
     *
     * @throws ApiException
     */
    public function validate(DataObjectAbstract $dto, array $groups = []);
}