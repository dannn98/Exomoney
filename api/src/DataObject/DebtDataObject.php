<?php

namespace App\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class DebtDataObject extends DataObjectAbstract
{
    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    public ?string $title;

    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Positive(message: 'Pole musi być typu int.')]
    public ?int $team_id;

    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    public ?array $debts;
}