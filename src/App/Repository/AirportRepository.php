<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Repository;

use BestEverWorld\AirportManager\Api\Request\AirportListRequest;
use BestEverWorld\AirportManager\App\Entity\Airport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

class AirportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Airport::class);
    }

    /**
     * @throws \Exception
     */
    public function createListQueryBuilder(AirportListRequest $airportListRequest): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if ($airportListRequest->getFilter()->getUuid()) {
            $queryBuilder
                ->andWhere('a.uuid = :uuid')
                ->setParameter(
                    'uuid',
                    $airportListRequest->getFilter()->getUuid(),
                    UuidType::NAME
                );
        }

        if ($airportListRequest->getFilter()->getName()) {
            $queryBuilder
                ->andWhere('a.name = :name')
                ->setParameter(
                    'name',
                    $airportListRequest->getFilter()->getName(),
                    Types::STRING
                );
        }

        if ($airportListRequest->getFilter()->getCode()) {
            $queryBuilder
                ->andWhere('a.code = :code')
                ->setParameter(
                    'code',
                    $airportListRequest->getFilter()->getCode(),
                    Types::STRING
                );
        }

        if ($airportListRequest->getFilter()->getCity()) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->like(
                        (string) $queryBuilder->expr()->lower('a.city'),
                        ':city'
                    )
                )
                ->setParameter(
                    'city',
                    $this->prepareStringForLikeExpression(
                        (string) $airportListRequest->getFilter()->getCity()
                    ),
                    Types::STRING
                );
        }

        if ($airportListRequest->getFilter()->getCountry()) {
            $queryBuilder
                ->andWhere('a.country = :country')
                ->setParameter(
                    'country',
                    $airportListRequest->getFilter()->getCountry(),
                    Types::STRING
                );
        }

        if ($airportListRequest->getFilter()->getCreatedBy()) {
            $queryBuilder
                ->andWhere('a.createdBy = :createdBy')
                ->setParameter(
                    'createdBy',
                    $airportListRequest->getFilter()->getCreatedBy(),
                    Types::STRING
                );
        }

        if ($airportListRequest->getFilter()->getUpdatedBy()) {
            $queryBuilder
                ->andWhere('a.updatedBy = :updatedBy')
                ->setParameter(
                    'updatedBy',
                    $airportListRequest->getFilter()->getUpdatedBy(),
                    Types::STRING
                );
        }

        if ($airportListRequest->getOrder()) {
            foreach ($airportListRequest->getOrder() as $order) {
                $queryBuilder->addOrderBy(sprintf('a.%s', $order->getFieldAttribute()), $order->getDirection());
            }
        } else {
            $queryBuilder->addOrderBy('a.createdAt', 'DESC');
        }

        return $queryBuilder;
    }

    private function prepareStringForLikeExpression(string $value): string
    {
        /*
         * Make a sense to move to separate service or trait
         */
        return '%' . mb_strtolower(trim($value)) . '%';
    }
}
