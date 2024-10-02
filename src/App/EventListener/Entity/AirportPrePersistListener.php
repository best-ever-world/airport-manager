<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\Airport;
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
    entity: Airport::class,
    priority: 0,
)]
class AirportPrePersistListener
{
    public function onPrePersist(Airport $airport, PrePersistEventArgs $event): void
    {
        $this->setCreateTime($airport);
        $this->setUpdateTime($airport);
    }

    private function setCreateTime(Airport $airport): void
    {
        if (null === $airport->getCreatedAt()) {
            $airport->setCreatedAt(new \DateTimeImmutable());
        }
    }

    private function setUpdateTime(Airport $airport): void
    {
        if (null === $airport->getUpdatedAt()) {
            $airport->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
