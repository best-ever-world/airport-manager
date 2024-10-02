<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener;

use BestEverWorld\AirportManager\App\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UserRegisterEventListener
{
    #[AsEventListener(event: UserRegisterEvent::class, priority: 100)]
    public function onUserRegisterEvent(UserRegisterEvent $userRegisterEvent): void
    {
        $user = $userRegisterEvent->getUser();
    }
}
