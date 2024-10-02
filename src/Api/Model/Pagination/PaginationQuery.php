<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Pagination;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class PaginationQuery
{
    private const int CURRENT_PAGE = 1;
    private const int MAX_PER_PAGE = 25;

    public function __construct(
        #[Assert\GreaterThan(-1)]
        #[Assert\Type('integer')]
        #[SerializedName('current_page')]
        private readonly int $currentPage = self::CURRENT_PAGE,
        #[Assert\GreaterThan(0)]
        #[Assert\Type('integer')]
        #[SerializedName('max_per_page')]
        private readonly int $maxPerPage = self::MAX_PER_PAGE,
    ) {
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }
}
