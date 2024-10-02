<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Repository;

use BestEverWorld\AirportManager\Api\Request\UserListRequest;
use BestEverWorld\AirportManager\App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws \Exception
     */
    public function createListQueryBuilder(UserListRequest $userListRequest): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if ($userListRequest->getFilter()->getUuid()) {
            $queryBuilder
                ->andWhere('u.uuid = :uuid')
                ->setParameter(
                    'uuid',
                    $userListRequest->getFilter()->getUuid(),
                    UuidType::NAME
                );
        }

        if ($userListRequest->getFilter()->getUsername()) {
            $queryBuilder
                ->andWhere('u.username = :username')
                ->setParameter(
                    'username',
                    $userListRequest->getFilter()->getUsername(),
                    Types::STRING
                );
        }

        if ($userListRequest->getFilter()->getFirstName()) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->like(
                        (string) $queryBuilder->expr()->lower('u.firstName'),
                        ':firstName'
                    )
                )
                ->setParameter(
                    'firstName',
                    $this->prepareStringForLikeExpression(
                        (string) $userListRequest->getFilter()->getFirstName()
                    ),
                    Types::STRING
                );
        }

        if ($userListRequest->getFilter()->getLastName()) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->like(
                        (string) $queryBuilder->expr()->lower('u.lastName'),
                        ':lastName'
                    )
                )
                ->setParameter(
                    'lastName',
                    $this->prepareStringForLikeExpression(
                        (string) $userListRequest->getFilter()->getLastName()
                    ),
                    Types::STRING
                );
        }

        if ($userListRequest->getOrder()) {
            foreach ($userListRequest->getOrder() as $order) {
                $queryBuilder->addOrderBy(sprintf('u.%s', $order->getFieldAttribute()), $order->getDirection());
            }
        } else {
            $queryBuilder->addOrderBy('u.createdAt', 'DESC');
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
