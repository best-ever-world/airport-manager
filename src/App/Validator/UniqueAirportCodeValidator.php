<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Validator;

use BestEverWorld\AirportManager\App\Entity\Airport;
use BestEverWorld\AirportManager\App\Repository\AirportRepository;
use BestEverWorld\AirportManager\App\Validator\Constraint\UniqueAirportCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueAirportCodeValidator extends ConstraintValidator
{
    public function __construct(
        private readonly AirportRepository $airportRepository,
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

        if (!$constraint instanceof UniqueAirportCode) {
            throw new UnexpectedTypeException($constraint, UniqueAirportCode::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($constraint, 'string');
        }

        /** @var Airport|null $airport */
        $airport = $this->airportRepository->findOneBy(['code' => $value]);
        if ($airport instanceof Airport) {
            $this->context
                ->buildViolation($constraint->message ?? UniqueAirportCode::ERROR_MESSAGE)
                ->setCode(UniqueAirportCode::ERROR_CODE)
                ->addViolation();
        }
    }
}
