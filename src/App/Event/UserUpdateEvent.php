<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Event;

use BestEverWorld\AirportManager\App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class UserUpdateEvent extends Event
{
    /**
     * @param array<array-key, mixed>|null $changeSet
     */
    public function __construct(
        private readonly User $user,
        private readonly ?array $changeSet = null,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return array<array-key, mixed>|null
     */
    public function getChangeSet(): ?array
    {
        return $this->changeSet;
    }
}
