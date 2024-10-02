<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\Country;
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
    entity: Country::class,
    priority: 0,
)]
class CountryPrePersistListener
{
    public function onPrePersist(Country $country, PrePersistEventArgs $event): void
    {
        $this->setCreateTime($country);
        $this->setUpdateTime($country);
    }

    private function setCreateTime(Country $country): void
    {
        if (null === $country->getCreatedAt()) {
            $country->setCreatedAt(new \DateTimeImmutable());
        }
    }

    private function setUpdateTime(Country $country): void
    {
        if (null === $country->getUpdatedAt()) {
            $country->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
