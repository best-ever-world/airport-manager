<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserTokenNotFoundException extends HttpException
{
    private const int STATUS_CODE = 403;
    private const string MESSAGE = 'User Token not found.';

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
