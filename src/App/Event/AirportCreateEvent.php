<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Event;

use BestEverWorld\AirportManager\App\Entity\Airport;
use Symfony\Contracts\EventDispatcher\Event;

final class AirportCreateEvent extends Event
{
    public function __construct(
        private readonly Airport $airport,
    ) {
    }

    public function getAirport(): Airport
    {
        return $this->airport;
    }
}
