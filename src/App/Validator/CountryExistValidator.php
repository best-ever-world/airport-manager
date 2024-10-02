<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Validator;

use BestEverWorld\AirportManager\App\Entity\Country;
use BestEverWorld\AirportManager\App\Repository\CountryRepository;
use BestEverWorld\AirportManager\App\Validator\Constraint\CountryExist;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CountryExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
    ) {
    }

    /**
     * @param string|null $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CountryExist) {
            throw new UnexpectedTypeException($constraint, CountryExist::class);
        }

        if (!Uuid::isValid($value)) {
            throw new UnexpectedValueException($constraint, Uuid::class);
        }

        /** @var Country|null $country */
        $country = $this->countryRepository->findOneBy(['uuid' => $value]);
        if (!$country instanceof Country) {
            $this->context
                ->buildViolation($constraint->message ?? CountryExist::ERROR_MESSAGE)
                ->setCode(CountryExist::ERROR_CODE)
                ->addViolation();
        }
    }
}
