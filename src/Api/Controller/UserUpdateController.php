<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Controller;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\Api\Request\UserUpdateRequest;
use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Event\UsernameUpdateEvent;
use BestEverWorld\AirportManager\App\Event\UserUpdateEvent;
use BestEverWorld\AirportManager\App\Service\EtagGenerator;
use BestEverWorld\AirportManager\App\Service\RequestParamResolver;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class UserUpdateController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EtagGenerator $etagGenerator,
        private readonly RequestParamResolver $requestParamResolver,
    ) {
    }

    #[
        Route(
            path: '/api/users/{uuid}',
            name: 'api.users.update',
            requirements: [
                'uuid' => Requirement::UUID,
            ],
            methods: [
                Request::METHOD_PATCH,
            ],
            condition: 'service("route_checker.default_route_checker").check(request)',
        ),
        IsGranted(
            attribute: UserGroupModel::UPDATE_ITEM,
            subject: 'user',
            message: 'Forbidden',
            statusCode: Response::HTTP_FORBIDDEN,
        ),
        OA\Tag(name: 'Users'),
        OA\RequestBody(
            content: [
                new OA\XmlContent(
                    ref: new Model(
                        type: UserUpdateRequest::class,
                        groups: [
                            UserGroupModel::UPDATE_ITEM,
                        ],
                    ),
                ),
                new OA\JsonContent(
                    ref: new Model(
                        type: UserUpdateRequest::class,
                        groups: [
                            UserGroupModel::UPDATE_ITEM,
                        ],
                    ),
                ),
            ],
        ),
        OA\Response(
            response: Response::HTTP_OK,
            description: 'Success response',
            content: [
                new OA\XmlContent(
                    ref: new Model(
                        type: User::class,
                        groups: [
                            UserGroupModel::VIEW_ITEM,
                        ],
                    ),
                ),
                new OA\JsonContent(
                    ref: new Model(
                        type: User::class,
                        groups: [
                            UserGroupModel::VIEW_ITEM,
                        ],
                    ),
                ),
            ],
        ),
        OA\Response(
            response: Response::HTTP_BAD_REQUEST,
            description: 'Bad Request',
        ),
        OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
        ),
        OA\Response(
            response: Response::HTTP_FORBIDDEN,
            description: 'Forbidden',
        ),
        OA\Response(
            response: Response::HTTP_NOT_FOUND,
            description: 'Not found',
        ),
        OA\Response(
            response: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: 'Unprocessable entity',
        ),
        OA\Response(
            response: Response::HTTP_INTERNAL_SERVER_ERROR,
            description: 'Internal server error',
        ),
    ]
    public function index(
        Request $request,
        #[MapEntity(
            class: User::class,
            expr: 'repository.findOneBy({"uuid": uuid})',
        )]
        User $user,
        #[MapRequestPayload(
            validationGroups: [
                UserGroupModel::UPDATE_ITEM,
            ],
            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        )]
        UserUpdateRequest $userUpdateRequest,
    ): Response {
        $this->entityManager->beginTransaction();
        try {
            /*
             * This is a highly simplified implementation. There are numerous potential pitfalls. For instance, it is
             * essential to verify that the user's email address exists and that the user has access to it. There is a
             * possibility that the email may be valid and available within the system but may not exist in the
             * real world, or the user might not have access to the specified email address.
             *
             * This is quite an interesting scenario: the data from the update request is fully valid according to our
             * existing data validators, and it also completely matches the user's existing data. In other words,
             * the data is identical. In such a case, there's no point in updating the data and creating a new event,
             * as the data won't actually be changed. It would be more efficient to skip these actions and send
             * a response with a 304 status code, signaling that the data has not been modified since there's no reason
             * to do so. However, we'll skip this step to simplify the implementation of this task. I believe
             * the correct approach is clear to everyone. :-)
             */
            $userUpdateRequest->getFirstName() ? $user->setFirstName($userUpdateRequest->getFirstName()) : null;
            $userUpdateRequest->getLastName() ? $user->setLastName($userUpdateRequest->getLastName()) : null;
            $userUpdateRequest->getUsername() ? $user->setUsername($userUpdateRequest->getUsername()) : null;

            $this->entityManager->getUnitOfWork()->computeChangeSets();
            $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($user);

            $this->entityManager->flush();
            $this->entityManager->commit();

            /*
             * A new user data change event is generated. This event encapsulates the user object's updated state along
             * with a diff representing the changes made. Specifically, it includes both the previous and current states
             * of the user object. This event can be intercepted for various purposes such as creating user activity
             * logs, sending email notifications, and more.
             */

            $this->eventDispatcher->dispatch(new UserUpdateEvent($user, $changeSet));

            $userUpdateRequest->getUsername() ?
                $this->eventDispatcher->dispatch(new UsernameUpdateEvent($user)) :
                null;

            $content = $this->serializer->serialize(
                $user,
                $this->requestParamResolver->resolveAcceptedFormat($request),
                (new ObjectNormalizerContextBuilder())->withGroups([
                    UserGroupModel::VIEW_ITEM,
                ])->toArray()
            );
        } catch (\Throwable $throwable) {
            $this->entityManager->rollback();

            $this->logger->error(
                $throwable->getMessage(),
                [
                    'request' => $this->serializer->serialize($request, 'json'),
                ],
            );

            throw $throwable;
        }

        return (new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => $this->requestParamResolver->resolveSuccessContentType($request)]
        ))
            ->setEtag($this->etagGenerator->generate($user))
            ->setCache([])
            ->setLastModified($user->getUpdatedAt());
    }
}
