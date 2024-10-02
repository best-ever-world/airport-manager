<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener;

use BestEverWorld\AirportManager\App\Event\UserPasswordResetEvent;
use BestEverWorld\AirportManager\App\Service\UserTokenInvalidator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UserPasswordResetEventListener
{
    public function __construct(
        private readonly UserTokenInvalidator $userTokenInvalidator,
    ) {
    }

    #[AsEventListener(event: UserPasswordResetEvent::class, priority: 100)]
    public function onUserPasswordResetEvent(UserPasswordResetEvent $userPasswordResetEvent): void
    {
        $user = $userPasswordResetEvent->getUser();

        /*
         * What actions can we take in this situation? This presents an excellent opportunity for consideration. It
         * might be prudent to notify related systems and cache systems that the user has changed their password and
         * a subsequent system login is required.
         */

        $this->userTokenInvalidator->invalidateUserTokens($user);
    }
}
