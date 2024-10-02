<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Service;

use BestEverWorld\AirportManager\App\Entity\UpdatedAtAwareInterface;
use BestEverWorld\AirportManager\App\Entity\UuidAwareInterface;

class EtagGenerator
{
    public function generate(UpdatedAtAwareInterface&UuidAwareInterface $entity): string
    {
        /*
         * How we are going to manage caching data? How are we going to allow for clients
         * to interface with endpoint and manage cached data on a client side? Let's
         * assume we have some kind of handlers to manage ETag, Cache-Control, etc....
         * Makes sense to move this logic out of controller action to avoid creating a
         * fat controller. It would be better to manage cache, tags, headers and other
         * response params at the right time before sending the response to client...
         */

        return sprintf(
            '%s:%s',
            $entity->getUuid(),
            md5((string) $entity->getUpdatedAt()->getTimestamp())
        );
    }
}
