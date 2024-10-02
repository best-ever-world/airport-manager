<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Security\Voter;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Model\UserRoleModel;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserUpdateRoleVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (UserGroupModel::UPDATE_ROLE !== $attribute) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $currentUser */
        $currentUser = $token->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        /** @var User $requestedUser */
        $requestedUser = $subject;

        if (in_array(UserRoleModel::ROLE_ADMIN, $currentUser->getRoles(), true)) {
            return true;
        }

        return false;
    }
}
