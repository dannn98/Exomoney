<?php

namespace App\Service\RandomCodeGenerator\CodeGenerators;

interface CodeGeneratorInterface
{
    const CODE_LENGTH = 12;

    /**
     * @return string
     */
    public function getCode(): string;
}