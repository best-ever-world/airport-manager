<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

/**
 * Make a sense to mark entities by some interfaces, implement those interfaces
 * and use a generic approach to manipulate entity data... But how to take care
 * about it?
 */
#[AsEntityListener(
    event: Events::prePersist,
    method: 'onPrePersist',
    entity: User::class,
    priority: 0,
)]
class UserPrePersistListener
{
    public function onPrePersist(User $user, PrePersistEventArgs $event): void
    {
        $this->setCreateTime($user);
        $this->setUpdateTime($user);
    }

    private function setCreateTime(User $user): void
    {
        if (null === $user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTimeImmutable());
        }
    }

    private function setUpdateTime(User $user): void
    {
        if (null === $user->getUpdatedAt()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
