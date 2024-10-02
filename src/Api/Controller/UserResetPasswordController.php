<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Controller;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Event\UserPasswordResetEvent;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\ByteString;

#[AsController]
class UserResetPasswordController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EtagGenerator $etagGenerator,
        private readonly RequestParamResolver $requestParamResolver,
    ) {
    }

    #[
        Route(
            path: '/api/users/{uuid}/reset-password',
            name: 'api.users.reset-password',
            requirements: [
                'uuid' => Requirement::UUID,
            ],
            methods: [
                Request::METHOD_PATCH,
            ],
            condition: 'service("route_checker.default_route_checker").check(request)',
        ),
        IsGranted(
            attribute: UserGroupModel::RESET_PASSWORD,
            message: 'Forbidden',
            statusCode: Response::HTTP_FORBIDDEN,
        ),
        OA\Tag(name: 'Users'),
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
    ): Response {
        $this->entityManager->beginTransaction();
        try {
            /*
             * How do we want to reset a user's password? For simplicity, let's assign a random password to the user.
             * However, is this really the correct and proper approach in this situation? Most likely not. In real-world
             * systems, password reset operations require multiple actions on the user's part and cannot be completed
             * within a single endpoint. Typically, password reset or recovery operations involve multiple steps:
             * verifying the user's communication channel (email or SMS), two-factor authentication, and potentially
             * using authentication apps from providers like Google or Microsoft. Third-party providers
             * may also be used. Additional steps may include the use of password recovery codes, recording temporary
             * passwords and codes in separate data storage tables, and more.
             */
            $user
                ->setPassword($this->userPasswordHasher->hashPassword($user, ByteString::fromRandom(64)->toString()))
            ;

            $this->entityManager->flush();
            $this->entityManager->commit();

            /*
             * User password has been changed. System may require additional actions such as cache invalidation or
             * re-authentication with linked systems. Subsequent operations may necessitate cache refreshes and
             * re-establishment of session tokens with affiliated services.
             */

            $this->eventDispatcher->dispatch(new UserPasswordResetEvent($user));

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
