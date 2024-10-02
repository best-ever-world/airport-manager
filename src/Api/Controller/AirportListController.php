<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Controller;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use BestEverWorld\AirportManager\Api\Model\Pagination\Pagination;
use BestEverWorld\AirportManager\Api\Request\AirportListRequest;
use BestEverWorld\AirportManager\Api\Response\AirportListResponse;
use BestEverWorld\AirportManager\App\Repository\AirportRepository;
use BestEverWorld\AirportManager\App\Service\PaginationFactory;
use BestEverWorld\AirportManager\App\Service\RequestParamResolver;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class AirportListController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly AirportRepository $airportRepository,
        private readonly PaginationFactory $paginationFactory,
        private readonly RequestParamResolver $requestParamResolver,
    ) {
    }

    #[
        Route(
            path: '/api/airports',
            name: 'api.airports.list',
            requirements: [
                'uuid' => Requirement::UUID,
            ],
            methods: [
                Request::METHOD_GET,
            ],
            condition: 'service("route_checker.default_route_checker").check(request)',
        ),
        IsGranted(
            attribute: AirportGroupModel::LIST_ITEM,
            message: 'Forbidden',
            statusCode: Response::HTTP_FORBIDDEN,
        ),
        OA\Tag(name: 'Airports'),
        OA\Response(
            response: Response::HTTP_OK,
            description: 'Success response',
            content: [
                new OA\XmlContent(
                    ref: new Model(
                        type: AirportListResponse::class,
                        groups: [
                            AirportGroupModel::LIST_ITEM,
                            Pagination::LIST_ITEM,
                        ],
                    ),
                ),
                new OA\JsonContent(
                    ref: new Model(
                        type: AirportListResponse::class,
                        groups: [
                            AirportGroupModel::LIST_ITEM,
                            Pagination::LIST_ITEM,
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
        #[MapQueryString(
            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        )]
        AirportListRequest $airportListRequest = new AirportListRequest(),
    ): Response {
        try {
            $pagination = $this->paginationFactory->create(
                $this->airportRepository->createListQueryBuilder($airportListRequest),
                $airportListRequest->getPaginationQuery(),
            );

            $content = $this->serializer->serialize(
                new AirportListResponse($pagination->getPaginatedData(), $pagination),
                $this->requestParamResolver->resolveAcceptedFormat($request),
                (new ObjectNormalizerContextBuilder())->withGroups([
                    AirportGroupModel::LIST_ITEM,
                    Pagination::LIST_ITEM,
                ])->toArray()
            );
        } catch (\Throwable $throwable) {
            $this->logger->error(
                $throwable->getMessage(),
                [
                    'request' => $this->serializer->serialize($request, 'json'),
                ],
            );

            throw $throwable;
        }

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => $this->requestParamResolver->resolveSuccessContentType($request)]
        );
    }
}
