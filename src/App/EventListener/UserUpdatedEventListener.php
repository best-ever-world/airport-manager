<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\EventListener;

use BestEverWorld\AirportManager\App\Event\UserUpdateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UserUpdatedEventListener
{
    #[AsEventListener(event: UserUpdateEvent::class, priority: 100)]
    public function onUserUpdateEvent(UserUpdateEvent $userUpdateEvent): void
    {
        $user = $userUpdateEvent->getUser();
        $changeSet = $userUpdateEvent->getChangeSet();

        /*
         * This presents an excellent opportunity and a strategic location to handle user data change events. Potential
         * actions include logging user activity or sending out activity email notifications. To optimize application
         * performance and enable asynchronous data processing, leveraging message queues or brokers
         * such as Apache Kafka, RabbitMQ, or SQS is highly recommended.
         */
    }
}
