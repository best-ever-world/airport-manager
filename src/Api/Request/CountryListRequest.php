<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\Filter\CountryFilter;
use BestEverWorld\AirportManager\Api\Model\Order\CountryOrder;
use BestEverWorld\AirportManager\Api\Model\Pagination\PaginationQuery;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CountryListRequest
{
    public function __construct(
        #[SerializedName('filter')]
        private readonly CountryFilter $filter = new CountryFilter(),
        #[SerializedName('page')]
        private readonly PaginationQuery $paginationQuery = new PaginationQuery(),
        /** @var array<CountryOrder> */
        #[Assert\Valid()]
        #[SerializedName('order')]
        private readonly array $order = [],
    ) {
    }

    public function getFilter(): CountryFilter
    {
        return $this->filter;
    }

    public function getPaginationQuery(): PaginationQuery
    {
        return $this->paginationQuery;
    }

    /**
     * @return CountryOrder[]
     */
    public function getOrder(): array
    {
        return $this->order;
    }
}
