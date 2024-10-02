<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Order;

use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class AirportOrder
{
    private const array FIELDS = [
        'uuid',
        'created_at',
        'updated_at',
        'name',
        'code',
        'city',
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
        'code' => 'code',
        'city' => 'city',
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
