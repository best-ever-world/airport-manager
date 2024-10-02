<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Security\Voter;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AirportListVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (AirportGroupModel::LIST_ITEM !== $attribute) {
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
