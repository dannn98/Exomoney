<?php


namespace App\DataObject;

use Symfony\Component\Validator\Constraints as Assert;

class RepaymentDataObject extends DataObjectAbstract
{
    #[Assert\NotBlank(message: 'Pole nie może być puste')]
    public ?string $uid;

    #[Assert\NotBlank(message: 'Pole nie może być puste')]
    #[Assert\Positive(message: 'Pole musi zawierać wartość dodatnią')]
    public ?float $value;
}