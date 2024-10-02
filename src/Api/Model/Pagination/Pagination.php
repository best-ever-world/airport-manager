<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Pagination;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Pagination
{
    public const string LIST_ITEM = 'pagination:list:item';

    public function __construct(
        #[Groups([self::LIST_ITEM])]
        #[OA\Property(
            description: 'Total items count in the database',
            type: 'integer',
            maxLength: 255,
        )]
        #[SerializedName('total')]
        private readonly int $total,
        #[Groups([self::LIST_ITEM])]
        #[OA\Property(
            description: 'Items count in the current response',
            type: 'integer',
            maxLength: 255,
        )]
        #[SerializedName('count')]
        private readonly int $count,
        #[Groups([self::LIST_ITEM])]
        #[OA\Property(
            description: 'Current page"',
            type: 'integer',
            maxLength: 255,
        )]
        #[SerializedName('current_page')]
        private readonly int $currentPage,
        #[Groups([self::LIST_ITEM])]
        #[OA\Property(
            description: 'Page size',
            type: 'integer',
            maxLength: 255,
        )]
        #[SerializedName('max_per_page')]
        private readonly int $maxPerPage,
        #[Groups([self::LIST_ITEM])]
        #[OA\Property(
            description: 'Overall page count',
            type: 'integer',
            maxLength: 255,
        )]
        #[SerializedName('total_pages')]
        private readonly int $totalPages,
        /**
         * @var array<mixed>
         */
        #[Ignore]
        private readonly array $paginatedData,
    ) {
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return mixed[]
     */
    public function getPaginatedData(): array
    {
        return $this->paginatedData;
    }
}
