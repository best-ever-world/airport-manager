<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\Airport;
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
    entity: Airport::class,
    priority: 0,
)]
class AirportPreUpdateListener
{
    public function onPreUpdate(Airport $airport, PreUpdateEventArgs $event): void
    {
        $this->setUpdateTime($airport);
    }

    private function setUpdateTime(Airport $airport): void
    {
        if (null === $airport->getUpdatedAt()) {
            $airport->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
