<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;
use BestEverWorld\AirportManager\App\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterRequest
{
    public function __construct(
        #[Groups([
            UserGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('first_name')]
        #[Assert\NotBlank(
            message: 'Please enter a first name.',
            normalizer: 'trim',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 2,
            max: 64,
            minMessage: 'The minimum allowable length for a first name is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a first name is {{ limit }} characters.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        private readonly string $firstName,
        #[Groups([
            UserGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('last_name')]
        #[Assert\NotBlank(
            message: 'Please enter a last name.',
            normalizer: 'trim',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 2,
            max: 64,
            minMessage: 'The minimum allowable length for a last name is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a last name is {{ limit }} characters.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        private readonly string $lastName,
        #[Groups([
            UserGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('username')]
        #[Assert\NotBlank(
            message: 'Please enter an username.',
            normalizer: 'trim',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 8,
            max: 128,
            minMessage: 'The minimum allowable length for a username is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a username is {{ limit }} characters.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Email(
            message: 'Please enter a valid email address.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Constraint\UniqueUsername(
            message: 'This username already exists. Please choose a different username.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        private readonly string $username,
        #[Groups([
            UserGroupModel::CREATE_ITEM,
        ])]
        #[SerializedName('password')]
        #[Assert\NotBlank(
            message: 'Please enter a password.',
            normalizer: 'trim',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Length(
            min: 8,
            max: 128,
            minMessage: 'The minimum allowable length for a password is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a password is {{ limit }} characters.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::CREATE_ITEM],
        )]
        #[Assert\PasswordStrength(
            minScore: Assert\PasswordStrength::STRENGTH_VERY_STRONG,
            groups: [UserGroupModel::CREATE_ITEM],
            message: 'The password strength is too low. Please use a stronger password.',
        )]
        private readonly string $password,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
