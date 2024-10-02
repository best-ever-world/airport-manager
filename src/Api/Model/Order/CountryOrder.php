<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Order;

use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class CountryOrder
{
    private const array FIELDS = [
        'uuid',
        'created_at',
        'updated_at',
        'name',
        'alpha_2_code',
        'alpha_3_code',
        'numeric_code',
        'iso_3166_code',
        'region',
    ];

    private const array DIRECTIONS = [
        'ASC',
        'DESC',
    ];

    private const array FIELDS_TO_ATTRIBUTES = [
        'uuid' => 'uuid',
        'created_at' => 'createdAt',
        'updated_at' => 'updatedAt',
        'name' => 'name',
        'alpha_2_code' => 'alpha2Code',
        'alpha_3_code' => 'alpha3Code',
        'numeric_code' => 'numericCode',
        'iso_3166_code' => 'iso3166Code',
        'region' => 'region',
    ];

    public function __construct(
        #[Assert\NotBlank(allowNull: false, normalizer: 'trim')]
        #[Assert\Type('string')]
        #[Assert\Choice(self::FIELDS)]
        private string $field,
        #[Assert\NotBlank(allowNull: false, normalizer: 'trim')]
        #[Assert\Type('string')]
        #[Assert\Choice(self::DIRECTIONS)]
        private string $direction,
    ) {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }

    #[Ignore()]
    public function getFieldAttribute(): string
    {
        /*
         * Make a sense to move to separate service or trait or create abstract class
         */
        if (!isset(self::FIELDS_TO_ATTRIBUTES[$this->field])) {
            throw new \Exception('');
        }

        return self::FIELDS_TO_ATTRIBUTES[$this->field];
    }
}
