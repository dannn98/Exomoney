<?php

namespace App\DataObject;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class TeamDataObject extends DataObjectAbstract
{
    #[Assert\NotBlank(message: 'Pole nie może być puste.')]
    #[Assert\Length(
        min: self::MIN_STRING_LENGTH, max: self::MAX_STRING_LENGTH,
        minMessage: 'Pole jest za krótkie.', maxMessage: 'Pole jest za długie'
    )]
    public ?string $name;

    #[Assert\File(maxSize: '2M', maxSizeMessage: 'Niepoprawny rozmiar pliku.')]
    #[Assert\Image(
        mimeTypes: ['image/png', 'image/jpeg'],
        minWidth: self::AVATAR_WIDTH, maxWidth: self::AVATAR_WIDTH,
        maxHeight: self::AVATAR_HEIGHT, minHeight: self::AVATAR_HEIGHT,
        mimeTypesMessage: 'Niepoprawny format pliku.',
        maxWidthMessage: 'Niepoprawny rozmiar awatara.', minWidthMessage: 'Niepoprawny rozmiar awatara.',
        maxHeightMessage: 'Niepoprawny rozmiar awatara.', minHeightMessage: 'Niepoprawny rozmiar awatara.'
    )]
    public ?UploadedFile $avatar_file;
}