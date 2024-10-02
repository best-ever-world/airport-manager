<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Response;

use BestEverWorld\AirportManager\Api\Model\Pagination\Pagination;
use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UserListResponse
{
    public function __construct(
        /**
         * @var User[] $data
         */
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('data')]
        private readonly array $data,
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('meta')]
        private readonly Pagination $meta,
    ) {
    }

    /**
     * @return User[]
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
