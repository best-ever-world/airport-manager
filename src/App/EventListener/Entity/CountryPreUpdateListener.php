<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\Country;
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
    entity: Country::class,
    priority: 0,
)]
class CountryPreUpdateListener
{
    public function onPreUpdate(Country $country, PreUpdateEventArgs $event): void
    {
        $this->setUpdateTime($country);
    }

    private function setUpdateTime(Country $country): void
    {
        if (null === $country->getUpdatedAt()) {
            $country->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
