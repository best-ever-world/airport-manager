<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResponseCodeResolver
{
    public function resolveExceptionCode(\Throwable $throwable): int
    {
        return match (get_class($throwable)) {
            HttpException::class => Response::HTTP_BAD_REQUEST,
            NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }
}
