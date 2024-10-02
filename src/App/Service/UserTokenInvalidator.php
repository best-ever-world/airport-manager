<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Service;

use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserTokenInvalidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserTokenRepository $userTokenRepository,
    ) {
    }

    public function invalidateUserTokens(User $user): void
    {
        $userTokens = $this->userTokenRepository->findBy(['user' => $user]);

        foreach ($userTokens as $userToken) {
            $this->entityManager->remove($userToken);
        }

        $this->entityManager->flush();
    }
}
