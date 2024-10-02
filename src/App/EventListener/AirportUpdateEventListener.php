<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener;

use BestEverWorld\AirportManager\App\Event\AirportUpdateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AirportUpdateEventListener
{
    #[AsEventListener(event: AirportUpdateEvent::class, priority: 100)]
    public function onAirportUpdateEvent(AirportUpdateEvent $airportUpdateEvent): void
    {
        $airport = $airportUpdateEvent->getAirport();
    }
}
