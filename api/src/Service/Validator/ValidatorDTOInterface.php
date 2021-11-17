<?php

namespace App\Service\Validator;

use App\DataObject\DataObjectAbstract;

interface ValidatorDTOInterface
{
    public function validate(DataObjectAbstract $dto, array $groups = []);
}