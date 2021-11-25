<?php

namespace App\Service\Validator;

use App\DataObject\DataObjectAbstract;
use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorDTO implements ValidatorDTOInterface
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

        if (count($violations) > 0) {
            throw new ApiException('Validation exception', $this->buildViolationsArray($violations), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Build violations array
     *
     * @param ConstraintViolationListInterface $violations
     *
     * @return array
     */
    private function buildViolationsArray(ConstraintViolationListInterface $violations): array
    {
        $errors = array();

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}