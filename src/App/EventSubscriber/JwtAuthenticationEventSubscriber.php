<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventSubscriber;

use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Entity\UserToken;
use BestEverWorld\AirportManager\App\Exception\UserIsDisabledException;
use BestEverWorld\AirportManager\App\Exception\UserIsNotApprovedException;
use BestEverWorld\AirportManager\App\Exception\UserTokenIdentifierNotFoundException;
use BestEverWorld\AirportManager\App\Exception\UserTokenNotFoundException;
use BestEverWorld\AirportManager\App\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

final class JwtAuthenticationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserTokenRepository $userTokenRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
            Events::JWT_CREATED => 'onJwtCreated',
            Events::JWT_DECODED => 'onJwtDecoded',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        if ($user->isDisabled()) {
            throw new UserIsDisabledException();
        }

        if (!$user->isApproved()) {
            throw new UserIsNotApprovedException();
        }

        [$token] = array_values($event->getData());

        $data = $this->jwtManager->parse($token);
        if (!isset($data['identifier']) && !Uuid::isValid($data['identifier'])) {
            throw new UserTokenIdentifierNotFoundException();
        }

        $userToken = new UserToken(
            $user,
            $data['identifier'],
        );

        $this->entityManager->persist($userToken);
        $this->entityManager->flush();
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();

        /** @var User $user */
        $user = $event->getUser();

        $payload['uuid'] = $user->getUuid()->toString();
        $payload['username'] = $user->getUsername();
        $payload['approved'] = $user->isApproved();
        $payload['disabled'] = $user->isDisabled();
        $payload['identifier'] = Uuid::v7();

        $event->setData($payload);
    }

    public function onJwtDecoded(JWTDecodedEvent $event): void
    {
        /*
         * This is likely the worst place to do this work. The optimal solution would be the following: move the logic
         * to a service and use one of the following events: LoginSuccessEvent or CheckPassportEvent
         * or InteractiveLoginEvent or KernelEvent scope to capture user actions and check the state of user tokens.
         */
        if (
            in_array(
                $this->requestStack->getCurrentRequest()->getRequestUri(),
                ['/api/auth/login', '/api/auth/logout'],
                true
            )
        ) {
            return;
        }

        $data = $event->getPayload();
        if (!isset($data['identifier']) && !Uuid::isValid($data['identifier'])) {
            throw new UserTokenIdentifierNotFoundException();
        }

        /** @var UserToken|null $userToken */
        $userToken = $this->userTokenRepository->findOneBy(['identifier' => $data['identifier']]);
        if (!$userToken instanceof UserToken) {
            throw new UserTokenNotFoundException();
        }
    }
}
