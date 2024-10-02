<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Validator;

use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Repository\UserRepository;
use BestEverWorld\AirportManager\App\Validator\Constraint\UserPassword;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserPasswordValidator extends ConstraintValidator
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @param string|null $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UserPassword) {
            throw new UnexpectedTypeException($constraint, UserPassword::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($constraint, 'string');
        }

        /** @var User|null $user */
        $user = $this->userRepository->findOneBy(['uuid' => $this->requestStack->getCurrentRequest()->get('uuid')]);
        if (!$user instanceof User) {
            /*
             * Using the RequestStack object in this validator is considered a bad practice. There's no need to
             * repeatedly search for the user in the database or caches, as the user object has already been loaded from
             * the repository at the Controller Action level. It would be more efficient to pass this object to
             * the validator as a parameter. Alternatively, we could use a validator class instead of a validator
             * attribute for this kind of operation. However, for the sake of simplifying the overall logic,
             * I'm leaving it as is.
             */
            throw new NotFoundHttpException('The system cannot locate a user account with the specified parameters.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $value)) {
            $this->context
                ->buildViolation($constraint->message ?? UserPassword::ERROR_MESSAGE)
                ->setCode(UserPassword::ERROR_CODE)
                ->addViolation();
        }
    }
}
