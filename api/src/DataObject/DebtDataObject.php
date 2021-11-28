<?php

namespace App\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class DebtDataObject extends DataObjectAbstract
{
    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Length(
        min: self::MIN_STRING_LENGTH, max: self::MAX_STRING_LENGTH,
        minMessage: 'Pole jest za krótkie.', maxMessage: 'Pole jest za długie'
    )]
    public ?string $title;

    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Positive(message: 'Pole musi być typu int.')]
    public ?int $team_id;

    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    public ?array $debts;
}