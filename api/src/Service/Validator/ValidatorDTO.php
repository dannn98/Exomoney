<?php

namespace App\Service\Validator;

use App\DataObject\DataObjectAbstract;
use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorDTO
{
    private ValidatorInterface $validator;

    /**
     * ValidatorService constructor
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate DTO object
     *
     * @throws ApiException
     */
    public function validate(DataObjectAbstract $dto, array $groups = [])
    {
        $violations = $this->validator->validate($dto, groups: $groups);
        $errors = array();

        if (count($violations) > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            throw new ApiException('Validation exception', $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}