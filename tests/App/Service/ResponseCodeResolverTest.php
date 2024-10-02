<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Tests\App\Service;

use BestEverWorld\AirportManager\App\Service\ResponseCodeResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ResponseCodeResolverTest extends TestCase
{
    private ResponseCodeResolver $responseCodeResolver;

    protected function setUp(): void
    {
        $this->responseCodeResolver = new ResponseCodeResolver();
    }

    public function testResolveHttpExceptionCode(): void
    {
        $exception = new HttpException(400);
        $responseCode = $this->responseCodeResolver->resolveExceptionCode($exception);

        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseCode);
    }

    public function testResolveNotFoundHttpExceptionCode(): void
    {
        $exception = new NotFoundHttpException();
        $responseCode = $this->responseCodeResolver->resolveExceptionCode($exception);

        $this->assertSame(Response::HTTP_NOT_FOUND, $responseCode);
    }

    public function testResolveOtherExceptionCode(): void
    {
        $exception = new \Exception();
        $responseCode = $this->responseCodeResolver->resolveExceptionCode($exception);

        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $responseCode);
    }
}
