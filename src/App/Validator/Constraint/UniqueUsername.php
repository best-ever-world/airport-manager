<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Validator\Constraint;

use BestEverWorld\AirportManager\App\Validator\UniqueUsernameValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueUsername extends Constraint
{
    public const string ERROR_CODE = '94c5e58b-f892-4e25-8fd6-1d89c80bfe01';
    public const string ERROR_MESSAGE = 'This username already exists.';

    protected const array ERROR_NAMES = [
        self::ERROR_CODE => 'ERROR_CODE',
    ];

    #[HasNamedArguments]
    public function __construct(
        public string $message = 'This username already exists.',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return UniqueUsernameValidator::class;
    }
}
