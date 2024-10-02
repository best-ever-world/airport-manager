<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

#[ORM\Entity]
#[ORM\Table(name: 'user_token_refresh')]
class UserTokenRefresh extends RefreshToken
{
}
