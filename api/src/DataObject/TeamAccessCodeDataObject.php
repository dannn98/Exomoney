<?php

namespace App\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class TeamAccessCodeDataObject extends DataObjectAbstract
{
    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Positive(message: 'Pole musi być typu int.')]
    public ?int $team_id;

    #[Assert\Positive(message: 'Pole musi być typu int.')]
    public ?string $number_of_uses;

    #[Assert\DateTime(message: 'Pole musi być w formacie \'Y-m-d H:i:s\'.')]
    public ?\DateTimeInterface $expire_time;
}