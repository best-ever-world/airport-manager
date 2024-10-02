<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\Filter\AirportFilter;
use BestEverWorld\AirportManager\Api\Model\Order\AirportOrder;
use BestEverWorld\AirportManager\Api\Model\Pagination\PaginationQuery;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class AirportListRequest
{
    public function __construct(
        #[SerializedName('filter')]
        private readonly AirportFilter $filter = new AirportFilter(),
        #[SerializedName('page')]
        private readonly PaginationQuery $paginationQuery = new PaginationQuery(),
        /** @var array<AirportOrder> */
        #[Assert\Valid()]
        #[SerializedName('order')]
        private readonly array $order = [],
    ) {
    }

    public function getFilter(): AirportFilter
    {
        return $this->filter;
    }

    public function getPaginationQuery(): PaginationQuery
    {
        return $this->paginationQuery;
    }

    /**
     * @return AirportOrder[]
     */
    public function getOrder(): array
    {
        return $this->order;
    }
}
