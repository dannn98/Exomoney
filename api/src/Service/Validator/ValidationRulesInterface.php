<?php

namespace App\Service\Validator;

interface ValidationRulesInterface
{
    const MIN_STRING_LENGTH = 2;
    const MAX_STRING_LENGTH = 32;

    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 32;

    const AVATAR_WIDTH = 128;
    const AVATAR_HEIGHT = 128;
}