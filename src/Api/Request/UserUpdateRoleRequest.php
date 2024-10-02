<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\App\Model\UserRoleModel;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateRoleRequest
{
    public function __construct(
        #[Groups([
            UserGroupModel::UPDATE_ROLE,
        ])]
        #[SerializedName('role')]
        #[Assert\NotBlank(
            message: 'Please enter a user role.',
            normalizer: 'trim',
            groups: [UserGroupModel::UPDATE_ROLE],
        )]
        #[Assert\Charset(
            encodings: 'UTF-8',
            message: 'The detected encoding is invalid ({{ detected }}), allowed encodings are {{ encodings }}.',
            groups: [UserGroupModel::UPDATE_ROLE],
        )]
        #[Assert\Choice(
            choices: [UserRoleModel::ROLE_ADMIN, UserRoleModel::ROLE_OPERATOR],
            message: 'The value you selected is not a valid user role.',
            groups: [UserGroupModel::UPDATE_ROLE],
        )]
        private readonly string $role,
    ) {
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
