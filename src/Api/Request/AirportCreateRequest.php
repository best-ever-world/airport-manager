<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;
use BestEverWorld\AirportManager\App\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class AirportCreateRequest
{
    public function __construct(
        #[Groups([
            AirportGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('name')]
        #[Assert\NotBlank(
            message: 'Please enter a name.',
            normalizer: 'trim',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 2,
            max: 128,
            minMessage: 'The minimum allowable length for an name is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for an name is {{ limit }} characters.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        private readonly string $name,
        #[Groups([
            AirportGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('code')]
        #[Assert\NotBlank(
            message: 'Please enter a code.',
            normalizer: 'trim',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 3,
            max: 3,
            minMessage: 'The minimum allowable length for a code is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a code is {{ limit }} characters.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Constraint\UniqueAirportCode(
            message: 'This airport code already exists. Please choose a different airport code.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        private readonly string $code,
        #[Groups([
            AirportGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('city')]
        #[Assert\NotBlank(
            message: 'Please enter a city.',
            normalizer: 'trim',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 2,
            max: 128,
            minMessage: 'The minimum allowable length for a city is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a city is {{ limit }} characters.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        private readonly string $city,
        #[Groups([
            AirportGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('country')]
        #[Assert\NotBlank(
            message: 'Please enter a country.',
            normalizer: 'trim',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Uuid(
            message: 'Please enter a valid country identifier.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        #[Constraint\CountryExist(
            message: 'This country not exists. Please choose a valid country.',
            groups: [AirportGroupModel::CREATE_ITEM],
        )]
        private readonly string $country,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
