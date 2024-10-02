<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Controller;

use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Service\EtagGenerator;
use BestEverWorld\AirportManager\App\Service\RequestParamResolver;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class UserDisapproveController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly EtagGenerator $etagGenerator,
        private readonly RequestParamResolver $requestParamResolver,
    ) {
    }

    #[
        Route(
            path: '/api/users/{uuid}/disapprove',
            name: 'api.users.disapprove',
            requirements: [
                'uuid' => Requirement::UUID,
            ],
            methods: [
                Request::METHOD_PATCH,
            ],
            condition: 'service("route_checker.default_route_checker").check(request)',
        ),
        IsGranted(
            attribute: UserGroupModel::DISAPPROVE_ITEM,
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
            $user->setApproved(false);

            $this->entityManager->flush();
            $this->entityManager->commit();

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
