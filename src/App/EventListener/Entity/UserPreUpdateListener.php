<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Make a sense to mark entities by some interfaces, implement those interfaces
 * and use a generic approach to manipulate entity data... But how to take care
 * about it?
 */
#[AsEntityListener(
    event: Events::preUpdate,
    method: 'onPreUpdate',
    entity: User::class,
    priority: 0,
)]
class UserPreUpdateListener
{
    public function onPreUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $this->setUpdateTime($user);
    }

    private function setUpdateTime(User $user): void
    {
        if (null === $user->getUpdatedAt()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
