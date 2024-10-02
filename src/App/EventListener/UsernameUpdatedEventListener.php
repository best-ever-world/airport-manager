<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener;

use BestEverWorld\AirportManager\App\Event\UsernameUpdateEvent;
use BestEverWorld\AirportManager\App\Service\UserTokenInvalidator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UsernameUpdatedEventListener
{
    public function __construct(
        private readonly UserTokenInvalidator $userTokenInvalidator,
    ) {
    }

    #[AsEventListener(event: UsernameUpdateEvent::class, priority: 100)]
    public function onUsernameUpdateEvent(UsernameUpdateEvent $usernameUpdateEvent): void
    {
        $user = $usernameUpdateEvent->getUser();

        /*
         * This presents an excellent opportunity and a strategic location to handle user data change events. Potential
         * actions include logging user activity or sending out activity email notifications. To optimize application
         * performance and enable asynchronous data processing, leveraging message queues or brokers
         * such as Apache Kafka, RabbitMQ, or SQS is highly recommended.
         */

        $this->userTokenInvalidator->invalidateUserTokens($user);
    }
}
