<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Filter;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class AirportFilter
{
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('uuid')]
    private ?string $uuid = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('name')]
    private ?string $name = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('code')]
    private ?string $code = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('city')]
    private ?string $city = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('country')]
    private ?string $country = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('created_by')]
    private ?string $createdBy = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('updated_by')]
    private ?string $updatedBy = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }
}
