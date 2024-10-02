<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Repository;

use BestEverWorld\AirportManager\Api\Request\CountryListRequest;
use BestEverWorld\AirportManager\App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @throws \Exception
     */
    public function createListQueryBuilder(CountryListRequest $countryListRequest): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('c');

        if ($countryListRequest->getFilter()->getUuid()) {
            $queryBuilder
                ->andWhere('c.uuid = :uuid')
                ->setParameter(
                    'uuid',
                    $countryListRequest->getFilter()->getUuid(),
                    UuidType::NAME
                );
        }

        if ($countryListRequest->getFilter()->getName()) {
            $queryBuilder
                ->andWhere('c.name = :name')
                ->setParameter(
                    'name',
                    $countryListRequest->getFilter()->getName(),
                    Types::STRING
                );
        }

        if ($countryListRequest->getFilter()->getAlpha2Code()) {
            $queryBuilder
                ->andWhere('c.alpha2Code = :alpha2Code')
                ->setParameter(
                    'alpha2Code',
                    $countryListRequest->getFilter()->getAlpha2Code(),
                    Types::STRING
                );
        }

        if ($countryListRequest->getFilter()->getAlpha3Code()) {
            $queryBuilder
                ->andWhere('c.alpha3Code = :alpha3Code')
                ->setParameter(
                    'alpha3Code',
                    $countryListRequest->getFilter()->getAlpha3Code(),
                    Types::STRING
                );
        }

        if ($countryListRequest->getFilter()->getNumericCode()) {
            $queryBuilder
                ->andWhere('c.numericCode = :numericCode')
                ->setParameter(
                    'numericCode',
                    $countryListRequest->getFilter()->getNumericCode(),
                    Types::STRING
                );
        }

        if ($countryListRequest->getFilter()->getIso3166Code()) {
            $queryBuilder
                ->andWhere('c.iso3166Code = :iso3166Code')
                ->setParameter(
                    'iso3166Code',
                    $countryListRequest->getFilter()->getIso3166Code(),
                    Types::STRING
                );
        }

        if ($countryListRequest->getFilter()->getRegion()) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->like(
                        (string) $queryBuilder->expr()->lower('c.region'),
                        ':region'
                    )
                )
                ->setParameter(
                    'region',
                    $this->prepareStringForLikeExpression(
                        (string) $countryListRequest->getFilter()->getRegion()
                    ),
                    Types::STRING
                );
        }

        if ($countryListRequest->getOrder()) {
            foreach ($countryListRequest->getOrder() as $order) {
                $queryBuilder->addOrderBy(sprintf('c.%s', $order->getFieldAttribute()), $order->getDirection());
            }
        } else {
            $queryBuilder->addOrderBy('c.createdAt', 'DESC');
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
