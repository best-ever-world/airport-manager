<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Validator;

use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Repository\UserRepository;
use BestEverWorld\AirportManager\App\Validator\Constraint\UniqueUsername;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @param string|null $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value) {
            return;
        }

        if (!$constraint instanceof UniqueUsername) {
            throw new UnexpectedTypeException($constraint, UniqueUsername::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($constraint, 'string');
        }

        /** @var User|null $user */
        $user = $this->userRepository->findOneBy(['username' => $value]);
        if ($user instanceof User) {
            $this->context
                ->buildViolation($constraint->message ?? UniqueUsername::ERROR_MESSAGE)
                ->setCode(UniqueUsername::ERROR_CODE)
                ->addViolation();
        }
    }
}
