<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserTokenIdentifierNotFoundException extends HttpException
{
    private const int STATUS_CODE = 400;
    private const string MESSAGE = 'User Token identifier not found or malformed.';

    /**
     * @param array<array-key, string> $headers
     */
    public function __construct(
        int $statusCode = self::STATUS_CODE,
        string $message = self::MESSAGE,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0,
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
