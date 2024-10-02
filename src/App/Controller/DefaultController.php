<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Controller;

use BestEverWorld\AirportManager\App\Service\RequestParamResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class DefaultController
{
    public function __construct(
        private readonly RequestParamResolver $requestParamResolver,
    ) {
    }

    #[Route(
        path: '/',
        name: 'app.default',
        methods: [
            'GET',
        ],
        condition: 'service("route_checker.default_route_checker").check(request)',
    )]
    public function index(Request $request): Response
    {
        return new Response(
            null,
            Response::HTTP_NO_CONTENT,
            ['content-type' => $this->requestParamResolver->resolveSuccessContentType($request)]
        );
    }
}
