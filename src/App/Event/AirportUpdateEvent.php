<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Event;

use BestEverWorld\AirportManager\App\Entity\Airport;
use Symfony\Contracts\EventDispatcher\Event;

final class AirportUpdateEvent extends Event
{
    /**
     * @param array<array-key, mixed> $changeSet
     */
    public function __construct(
        private readonly Airport $airport,
        private readonly ?array $changeSet = null,
    ) {
    }

    public function getAirport(): Airport
    {
        return $this->airport;
    }

    /**
     * @return array<array-key, mixed>|null
     */
    public function getChangeSet(): ?array
    {
        return $this->changeSet;
    }
}
