<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;
use BestEverWorld\AirportManager\App\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class AirportUpdateRequest
{
    public function __construct(
        #[Groups([
            AirportGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('name')]
        #[Assert\Length(
            min: 2,
            max: 128,
            minMessage: 'The minimum allowable length for an name is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for an name is {{ limit }} characters.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        private readonly ?string $name = null,
        #[Groups([
            AirportGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('code')]
        #[Assert\Length(
            min: 3,
            max: 3,
            minMessage: 'The minimum allowable length for a code is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a code is {{ limit }} characters.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        #[Constraint\UniqueAirportCode(
            message: 'This airport code already exists. Please choose a different airport code.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        private readonly ?string $code = null,
        #[Groups([
            AirportGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('city')]
        #[Assert\Length(
            min: 2,
            max: 128,
            minMessage: 'The minimum allowable length for a city is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a city is {{ limit }} characters.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::UPDATE_ITEM],
        )]
        private readonly ?string $city = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }
}
