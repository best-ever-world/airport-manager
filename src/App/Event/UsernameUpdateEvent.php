<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Event;

use BestEverWorld\AirportManager\App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class UsernameUpdateEvent extends Event
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
