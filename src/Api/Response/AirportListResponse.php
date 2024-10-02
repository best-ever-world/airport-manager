<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Response;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use BestEverWorld\AirportManager\Api\Model\Pagination\Pagination;
use BestEverWorld\AirportManager\App\Entity\Airport;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class AirportListResponse
{
    public function __construct(
        /**
         * @var Airport[] $data
         */
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('data')]
        private readonly array $data,
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('meta')]
        private readonly Pagination $meta,
    ) {
    }

    /**
     * @return Airport[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getMeta(): Pagination
    {
        return $this->meta;
    }
}
