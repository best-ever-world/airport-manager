<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;
use BestEverWorld\AirportManager\App\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserUpdatePasswordRequest
{
    public function __construct(
        #[Groups([
            UserGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('old_password')]
        #[Assert\NotBlank(
            message: 'Please enter a old password.',
            normalizer: 'trim',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Constraint\UserPassword(
            message: 'This value should be the password or requested user.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        private readonly string $oldPassword,
        #[Groups([
            UserGroupModel::UPDATE_ITEM,
        ])]
        #[SerializedName('new_password')]
        #[Assert\NotBlank(
            message: 'Please enter a new password.',
            normalizer: 'trim',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Length(
            min: 8,
            max: 128,
            minMessage: 'The minimum allowable length for a password is {{ limit }} characters.',
            maxMessage: 'The maximum allowable length for a password is {{ limit }} characters.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::UPDATE_ITEM],
        )]
        #[Assert\PasswordStrength(
            minScore: Assert\PasswordStrength::STRENGTH_VERY_STRONG,
            groups: [UserGroupModel::UPDATE_ITEM],
            message: 'The password strength is too low. Please use a stronger password.',
        )]
        private readonly string $newPassword,
    ) {
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
