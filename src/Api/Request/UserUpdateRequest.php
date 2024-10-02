<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;
use BestEverWorld\AirportManager\App\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateRequest
{
    public function __construct(
        #[Groups([
            UserGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('first_name')]
        #[Assert\Length(
            min: 2,
            max: 64,
            minMessage: 'The minimum allowable length for a first name is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a first name is {{ limit }} characters.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        private readonly ?string $firstName = null,
        #[Groups([
            UserGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('last_name')]
        #[Assert\Length(
            min: 2,
            max: 64,
            minMessage: 'The minimum allowable length for a last name is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a last name is {{ limit }} characters.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        private readonly ?string $lastName = null,
        #[Groups([
            UserGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('username')]
        #[Assert\Length(
            min: 8,
            max: 128,
            minMessage: 'The minimum allowable length for a username is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a username is {{ limit }} characters.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Email(
            message: 'Please enter a valid email address.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Constraint\UniqueUsername(
            message: 'This username already exists. Please choose a different username.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        private readonly ?string $username = null,
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
