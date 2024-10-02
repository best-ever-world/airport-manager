<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Security;

use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Service\UserTokenInvalidator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LogoutEvent;

final class LogoutEventListener
{
    public function __construct(
        private readonly UserTokenInvalidator $userTokenInvalidator,
    ) {
    }

    #[AsEventListener(event: LogoutEvent::class, priority: 100)]
    public function onLogoutEvent(LogoutEvent $logoutEvent): void
    {
        /** @var User $user */
        $user = $logoutEvent->getToken()?->getUser();

        if (!$user instanceof User) {
            return;
        }

        /*
         * Is this solution optimal in terms of system performance? The answer is both yes and no. The exact response
         * hinges on the specific objectives. We can anticipate that upon deleting user tokens, there might be a need
         * for additional actions concerning the token owners. In such a scenario, it would be prudent to iterate over
         * all tokens and delete the user's tokens individually. Conversely, if no further actions are required, it
         * would be significantly more efficient to delete all tokens in a single iteration, thus avoiding a complete
         * traversal of the user's tokens.
         */

        $this->userTokenInvalidator->invalidateUserTokens($user);
    }
}
