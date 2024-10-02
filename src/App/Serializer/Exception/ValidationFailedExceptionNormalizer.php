<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Serializer\Exception;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationFailedExceptionNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * @param \Throwable $exception
     * @param array<array-key, mixed> $context
     * @return array<array-key, mixed>
     */
    public function normalize($exception, ?string $format = null, array $context = []): array
    {
        $errors = [];

        foreach ($context['exception']->getPrevious()->getViolations() as $violation) {
            $errors[] = [
                'code' => $violation->getCode(),
                'type' => '/api/problems/validation-failed',
                'title' => 'Validation failed',
                'message' => $violation->getMessage(),
                'value' => $violation->getInvalidValue(),
                'property' => $violation->getPropertyPath(),
                'status_code' => $context['exception']->getStatusCode(),
            ];
        }

        return [
            'code' => $context['exception']->getStatusCode(),
            'type' => '/api/problems/unprocessable-entity',
            'title' => 'Validation failed',
            'message' => 'Validation failed',
            'instance' => $this->requestStack->getCurrentRequest()->getRequestUri(),
            'errors' => $errors,
        ];
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException
            && $context['exception'] instanceof UnprocessableEntityHttpException
            && $context['exception']->getPrevious() instanceof ValidationFailedException
            && $context['exception']->getPrevious()->getViolations() instanceof ConstraintViolationList;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*' => true,
        ];
    }
}
