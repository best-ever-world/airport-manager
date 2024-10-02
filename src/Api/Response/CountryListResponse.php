<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Response;

use BestEverWorld\AirportManager\Api\Model\CountryGroupModel;
use BestEverWorld\AirportManager\Api\Model\Pagination\Pagination;
use BestEverWorld\AirportManager\App\Entity\Country;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class CountryListResponse
{
    public function __construct(
        /**
         * @var Country[] $data
         */
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('data')]
        private readonly array $data,
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('meta')]
        private readonly Pagination $meta,
    ) {
    }

    /**
     * @return Country[]
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
