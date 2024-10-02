<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

use Symfony\Component\Uid\Uuid;

interface UuidAwareInterface
{
    public function getUuid(): ?Uuid;
}
