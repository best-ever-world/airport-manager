<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model\Filter;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CountryFilter
{
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('uuid')]
    private ?string $uuid = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('name')]
    private ?string $name = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('alpha_2_code')]
    private ?string $alpha2Code = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('alpha_3_code')]
    private ?string $alpha3Code = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('numeric_code')]
    private ?string $numericCode = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('iso_3166_code')]
    private ?string $iso3166Code = null;
    #[Assert\NotBlank(allowNull: true, normalizer: 'trim')]
    #[SerializedName('region')]
    private ?string $region = null;

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

    public function getAlpha2Code(): ?string
    {
        return $this->alpha2Code;
    }

    public function setAlpha2Code(?string $alpha2Code): void
    {
        $this->alpha2Code = $alpha2Code;
    }

    public function getAlpha3Code(): ?string
    {
        return $this->alpha3Code;
    }

    public function setAlpha3Code(?string $alpha3Code): void
    {
        $this->alpha3Code = $alpha3Code;
    }

    public function getNumericCode(): ?string
    {
        return $this->numericCode;
    }

    public function setNumericCode(?string $numericCode): void
    {
        $this->numericCode = $numericCode;
    }

    public function getIso3166Code(): ?string
    {
        return $this->iso3166Code;
    }

    public function setIso3166Code(?string $iso3166Code): void
    {
        $this->iso3166Code = $iso3166Code;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }
}
