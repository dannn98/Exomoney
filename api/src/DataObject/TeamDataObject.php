<?php

namespace App\DataObject;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class TeamDataObject extends DataObjectAbstract
{
    //TODO: Dodać interfejs ze stałymi
    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Length(min: 4, max: 32, minMessage: 'Pole jest za krótkie.', maxMessage: 'Pole jest za długie')]
    public ?string $name;

    #[Assert\Image(
        //TODO: Zawęzić do wybranych typów
        minWidth: 128, maxWidth: 128, maxHeight: 128, minHeight: 128,
        maxWidthMessage: 'Niepoprawny rozmiar awatara.', minWidthMessage: 'Niepoprawny rozmiar awatara.',
        maxHeightMessage: 'Niepoprawny rozmiar awatara.', minHeightMessage: 'Niepoprawny rozmiar awatara.'
    )]
    public ?UploadedFile $avatar_file;
}