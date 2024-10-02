<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Filter;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class UserFilter
{
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('uuid')]
    private ?string $uuid = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('username')]
    private ?string $username = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('first_name')]
    private ?string $firstName = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('last_name')]
    private ?string $lastName = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
