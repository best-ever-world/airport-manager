<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Security\Voter;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserRegisterVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (UserGroupModel::REGISTER !== $attribute) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /*
         * Public resource, no authentication or extra access rights checking is needed.
         */
        return true;
    }
}
