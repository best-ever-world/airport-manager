<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Controller;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use BestEverWorld\AirportManager\Api\Request\AirportUpdateRequest;
use BestEverWorld\AirportManager\App\Entity\Airport;
use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Event\AirportUpdateEvent;
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
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class AirportUpdateController
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
            path: '/api/airports/{uuid}',
            name: 'api.airports.update',
            requirements: [
                'uuid' => Requirement::UUID,
            ],
            methods: [
                Request::METHOD_PATCH,
            ],
            condition: 'service("route_checker.default_route_checker").check(request)',
        ),
        IsGranted(
            attribute: AirportGroupModel::UPDATE_ITEM,
            message: 'Forbidden',
            statusCode: Response::HTTP_FORBIDDEN,
        ),
        OA\Tag(name: 'Airports'),
        OA\RequestBody(
            content: [
                new OA\XmlContent(
                    ref: new Model(
                        type: AirportUpdateRequest::class,
                        groups: [
                            AirportGroupModel::UPDATE_ITEM,
                        ],
                    ),
                ),
                new OA\JsonContent(
                    ref: new Model(
                        type: AirportUpdateRequest::class,
                        groups: [
                            AirportGroupModel::UPDATE_ITEM,
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
                        type: Airport::class,
                        groups: [
                            AirportGroupModel::VIEW_ITEM,
                        ],
                    ),
                ),
                new OA\JsonContent(
                    ref: new Model(
                        type: Airport::class,
                        groups: [
                            AirportGroupModel::VIEW_ITEM,
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
            class: Airport::class,
            expr: 'repository.findOneBy({"uuid": uuid})',
        )]
        Airport $airport,
        #[MapRequestPayload(
            validationGroups: [
                AirportGroupModel::UPDATE_ITEM,
            ],
            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        )]
        AirportUpdateRequest $airportUpdateRequest,
        #[CurrentUser]
        ?User $currentUser = null,
    ): Response {
        $this->entityManager->beginTransaction();
        try {
            $airportUpdateRequest->getName() ? $airport->setName($airportUpdateRequest->getName()) : null;
            $airportUpdateRequest->getCode() ? $airport->setCode($airportUpdateRequest->getCode()) : null;
            $airportUpdateRequest->getCity() ? $airport->setCity($airportUpdateRequest->getCity()) : null;

            $airport->setUpdatedBy($currentUser);

            $this->entityManager->getUnitOfWork()->computeChangeSets();
            $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($airport);

            $this->entityManager->flush();
            $this->entityManager->commit();

            $this->eventDispatcher->dispatch(new AirportUpdateEvent($airport, $changeSet));

            $content = $this->serializer->serialize(
                $airport,
                $this->requestParamResolver->resolveAcceptedFormat($request),
                (new ObjectNormalizerContextBuilder())->withGroups([
                    AirportGroupModel::VIEW_ITEM,
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
            ->setEtag($this->etagGenerator->generate($airport))
            ->setCache([])
            ->setLastModified($airport->getUpdatedAt());
    }
}
