<?php

namespace App\Service\RandomCodeGenerator;

use App\Service\RandomCodeGenerator\CodeGenerators\CodeGeneratorInterface;

class RandomCodeGeneratorService
{
    private CodeGeneratorInterface $codeGenerator;

    /**
     * RandomCodeGenerator constructor
     */
    public function __construct(CodeGeneratorInterface $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @param CodeGeneratorInterface $codeGenerator
     */
    public function setCodeGenerator(CodeGeneratorInterface $codeGenerator): void
    {
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->codeGenerator->getCode();
    }
}