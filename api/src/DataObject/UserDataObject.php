<?php

namespace App\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class UserDataObject extends DataObjectAbstract
{
    #[Assert\Email(message: "Pole musi być typu email")]
    #[Assert\NotBlank(message: "Pole nie może być puste")]
    public ?string $email;

    #[Assert\NotBlank(message: "Pole nie może być puste")]
    #[Assert\Length(
        min: self::MIN_STRING_LENGTH, max: self::MAX_STRING_LENGTH,
        minMessage: 'Pole jest za krótkie', maxMessage: 'Pole jest za długie'
    )]
    public ?string $nickname;

    #[Assert\NotBlank(message: "Pole nie może być puste")]
    #[Assert\Length(
        min: self::MIN_PASSWORD_LENGTH, max: self::MAX_PASSWORD_LENGTH,
        minMessage: 'Pole jest za krótkie', maxMessage: 'Pole jest za długie')]
    public ?string $password;
}