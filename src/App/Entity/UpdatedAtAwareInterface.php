<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

interface UpdatedAtAwareInterface
{
    public function getUpdatedAt(): ?\DateTimeImmutable;
}
