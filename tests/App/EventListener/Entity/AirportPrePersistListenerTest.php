<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Tests\App\EventListener\Entity;

use BestEverWorld\AirportManager\App\Entity\Airport;
use BestEverWorld\AirportManager\App\EventListener\Entity\AirportPrePersistListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class AirportPrePersistListenerTest extends TestCase
{
    private AirportPrePersistListener $airportPrePersistListener;

    protected function setUp(): void
    {
        $this->airportPrePersistListener = new AirportPrePersistListener();
    }

    public function testOnPrePersistSetsCreateTimeForAirportWithNullCreatedAt(): void
    {
        $airport = (new Airport(
            'Malta International Airport (Luqa Airport)',
            'MLA',
            'Luqa / Gudja',
        ))->setUuid(new Uuid('018fa4e0-97fb-75cb-a9d1-b7c7258b43f1'));

        $this->airportPrePersistListener->onPrePersist(
            $airport,
            new PrePersistEventArgs(
                new \stdClass(),
                $this->createMock(ObjectManager::class),
            )
        );

        $this->assertNotNull($airport->getCreatedAt());
    }

    public function testOnPrePersistSetsUpdateTimeForAirportWithNullUpdatedAt(): void
    {
        $airport = (new Airport(
            'Malta International Airport (Luqa Airport)',
            'MLA',
            'Luqa / Gudja',
        ))->setUuid(new Uuid('018fa4e0-97fb-75cb-a9d1-b7c7258b43f1'));

        $this->airportPrePersistListener->onPrePersist(
            $airport,
            new PrePersistEventArgs(
                new \stdClass(),
                $this->createMock(ObjectManager::class),
            )
        );

        $this->assertNotNull($airport->getUpdatedAt());
    }
}
