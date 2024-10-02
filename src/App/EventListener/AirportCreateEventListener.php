<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener;

use BestEverWorld\AirportManager\App\Event\AirportCreateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AirportCreateEventListener
{
    #[AsEventListener(event: AirportCreateEvent::class, priority: 100)]
    public function onAirportCreateEvent(AirportCreateEvent $airportCreateEvent): void
    {
        $airport = $airportCreateEvent->getAirport();
    }
}
