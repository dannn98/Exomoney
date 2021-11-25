<?php

namespace App\Serializer;

use App\Exception\FormException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormExceptionNormalizer implements NormalizerInterface
{
    /**
     * Normalize exception
     *
     * @param FormException $exception
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($exception, string $format = null, array $context = [])
    {
        $data = [];
        $errors = $exception->getErrors();

        foreach ($errors as $error) {
            $data[$error->getOrigin()->getName()][] = $error->getMessage();
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FormException;
    }
}