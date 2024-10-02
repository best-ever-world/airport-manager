<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Service;

use Symfony\Component\HttpFoundation\Request;

class RequestParamResolver
{
    public function resolveAcceptedFormat(Request $request): string
    {
        $accept = strtolower($request->headers->get('accept', 'application/json'));

        return match ($accept) {
            'application/xml' => 'xml',
            default => 'json',
        };
    }

    public function resolveExceptionContentType(Request $request): string
    {
        $accept = strtolower($request->headers->get('accept', 'application/json'));

        return match ($accept) {
            'application/xml' => 'application/problem+xml',
            default => 'application/problem+json',
        };
    }

    public function resolveSuccessContentType(Request $request): string
    {
        $accept = strtolower($request->headers->get('accept', 'application/json'));

        return match ($accept) {
            'application/xml' => 'application/xml',
            default => 'application/json',
        };
    }
}
