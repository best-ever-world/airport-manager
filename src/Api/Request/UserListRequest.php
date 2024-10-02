<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Request;

use BestEverWorld\AirportManager\Api\Model\Filter\UserFilter;
use BestEverWorld\AirportManager\Api\Model\Order\UserOrder;
use BestEverWorld\AirportManager\Api\Model\Pagination\PaginationQuery;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class UserListRequest
{
    public function __construct(
        #[SerializedName('filter')]
        private readonly UserFilter $filter = new UserFilter(),
        #[SerializedName('page')]
        private readonly PaginationQuery $paginationQuery = new PaginationQuery(),
        /** @var array<UserOrder> */
        #[Assert\Valid()]
        #[SerializedName('order')]
        private readonly array $order = [],
    ) {
    }

    public function getFilter(): UserFilter
    {
        return $this->filter;
    }

    public function getPaginationQuery(): PaginationQuery
    {
        return $this->paginationQuery;
    }

    /**
     * @return UserOrder[]
     */
    public function getOrder(): array
    {
        return $this->order;
    }
}
