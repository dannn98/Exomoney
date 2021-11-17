<?php

namespace App\DataObject;

interface DataObjectInterface
{
    const MIN_STRING_LENGTH = 4;
    const MAX_STRING_LENGTH = 32;

    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 32;

    const AVATAR_WIDTH = 128;
    const AVATAR_HEIGHT = 128;
}