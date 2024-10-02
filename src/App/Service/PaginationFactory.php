<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Service;

use BestEverWorld\AirportManager\Api\Model\Pagination\Pagination;
use BestEverWorld\AirportManager\Api\Model\Pagination\PaginationQuery;
use BestEverWorld\AirportManager\App\Exception\PaginationException;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\PagerfantaException;
use Pagerfanta\Pagerfanta;

class PaginationFactory
{
    public function create(QueryBuilder $queryBuilder, PaginationQuery $paginationQuery): Pagination
    {
        try {
            $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilder));

            $pagerfanta->setCurrentPage($paginationQuery->getCurrentPage());
            $pagerfanta->setMaxPerPage($paginationQuery->getMaxPerPage());

            return new Pagination(
                total: $pagerfanta->getNbResults(),
                count: iterator_count($pagerfanta->getIterator()),
                currentPage: $pagerfanta->getCurrentPage(),
                maxPerPage: $paginationQuery->getMaxPerPage(),
                totalPages: $pagerfanta->getNbPages(),
                paginatedData: (array) $pagerfanta->getCurrentPageResults(),
            );
        } catch (PagerfantaException $exception) {
            throw new PaginationException(400, $exception->getMessage());
        }
    }
}
