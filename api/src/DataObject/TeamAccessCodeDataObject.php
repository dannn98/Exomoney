<?php

namespace App\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class TeamAccessCodeDataObject extends DataObjectAbstract
{
    const ADD = 'Add';
    const JOIN = 'Join';

    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Positive(message: 'Pole musi być typu int.')]
    public ?int $team_id;

    #[Assert\NotBlank(message: 'Pole nie może być puste.', groups: [self::JOIN])]
    public ?string $code;

    #[Assert\Type(type: 'integer', message: 'Pole musi być typu int.')]
    #[Assert\Positive(message: 'Podana liczba musi być dodatnia.')]
    public ?int $number_of_uses;

    //TODO: Dodać Timezone jak będę koksem
    #[Assert\DateTime(message: 'Pole musi być w formacie \'Y-m-d H:i:s\'.')]
    public ?string $expire_time;
}