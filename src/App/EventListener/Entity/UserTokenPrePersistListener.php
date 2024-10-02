<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\UserToken;
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
    entity: UserToken::class,
    priority: 0,
)]
class UserTokenPrePersistListener
{
    public function onPrePersist(UserToken $userToken, PrePersistEventArgs $event): void
    {
        $this->setCreateTime($userToken);
        $this->setUpdateTime($userToken);
    }

    private function setCreateTime(UserToken $userToken): void
    {
        if (null === $userToken->getCreatedAt()) {
            $userToken->setCreatedAt(new \DateTimeImmutable());
        }
    }

    private function setUpdateTime(UserToken $userToken): void
    {
        if (null === $userToken->getUpdatedAt()) {
            $userToken->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
