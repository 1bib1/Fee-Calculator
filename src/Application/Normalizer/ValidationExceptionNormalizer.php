<?php

declare(strict_types=1);

namespace App\FeeCalculator\Application\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, string $format = null, array $context = array()): array
    {
        /** @var ValidationFailedException $object */
        $violations = $this->getMessagesAndViolations($object->getViolations());

        return [
            'message' => strlen($object->getMessage()) > 0 ? $object->getMessage() : 'Validation error.',
            'errors' => $violations,
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ValidationFailedException;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    private function getMessagesAndViolations(ConstraintViolationListInterface $constraintViolationList): array
    {
        $violations = [];
        /** @var ConstraintViolation $violation */
        foreach ($constraintViolationList as $violation) {
            $violations[$violation->getPropertyPath()] = [
                    'message' => $violation->getMessage(),
                    'code' => $violation->getCode(),
                ];
        }
        return $violations;
    }
}
