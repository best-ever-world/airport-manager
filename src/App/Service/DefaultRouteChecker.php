<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;
use Symfony\Component\HttpFoundation\Request;

#[AsRoutingConditionService(
    alias: 'route_checker.default_route_checker',
)]
class DefaultRouteChecker
{
    public function check(Request $request): bool
    {
        /*
         * We are super strong and smart guys, and we always trust to all headers,
         * or maybe we are super lazy and super crazy guys to go check incoming headers.
         */
        return true;
    }
}
