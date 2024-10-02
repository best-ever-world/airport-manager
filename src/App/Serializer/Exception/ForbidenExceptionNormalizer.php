<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Serializer\Exception;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ForbidenExceptionNormalizer implements NormalizerInterface
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

        return [
            'code' => $context['exception']->getStatusCode(),
            'type' => '/api/problems/forbidden',
            'title' => 'Forbidden',
            'message' => 'Forbidden',
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
            && $context['exception'] instanceof HttpException
            && 403 === $context['exception']->getStatusCode();
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*' => true,
        ];
    }
}
