<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use BestEverWorld\AirportManager\Api\Model\CountryGroupModel;
use BestEverWorld\AirportManager\App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'country')]
/**
 * Do we really needs in such scope of indices? Let's assume we are going
 * to operate some super-pooper system, and it will be strongly mandatory
 * to have big scope of indices...
 */
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A0', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A1', columns: ['updated_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A2', columns: ['alpha_2_code'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A3', columns: ['alpha_3_code'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A4', columns: ['numeric_code'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A5', columns: ['iso_3166_code'])]
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country implements UpdatedAtAwareInterface, UuidAwareInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups([
        CountryGroupModel::LIST_ITEM,
        CountryGroupModel::VIEW_ITEM,
        AirportGroupModel::LIST_ITEM,
        AirportGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[Groups([
        CountryGroupModel::LIST_ITEM,
        CountryGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[Groups([
        CountryGroupModel::LIST_ITEM,
        CountryGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('updated_at')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: Airport::class, mappedBy: 'country', fetch: 'EXTRA_LAZY')]
    private Collection $airports;

    public function __construct(
        #[ORM\Column(name: 'name', type: Types::STRING, length: 64, unique: true, nullable: false)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('name')]
        private string $name,
        #[ORM\Column(name: 'alpha_2_code', type: Types::STRING, length: 2, unique: true, nullable: false)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('alpha_2_code')]
        private string $alpha2Code,
        #[ORM\Column(name: 'alpha_3_code', type: Types::STRING, length: 3, unique: true, nullable: false)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('alpha_3_code')]
        private string $alpha3Code,
        #[ORM\Column(name: 'numeric_code', type: Types::STRING, length: 3, unique: true, nullable: false)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('numeric_code')]
        private string $numericCode,
        #[ORM\Column(name: 'iso_3166_code', type: Types::STRING, length: 16, unique: true, nullable: false)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('iso_3166_code')]
        private string $iso3166Code,
        #[ORM\Column(name: 'region', type: Types::STRING, length: 64, unique: false, nullable: true)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('region')]
        private ?string $region = null,
        #[ORM\Column(name: 'sub_region', type: Types::STRING, length: 128, unique: false, nullable: true)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('sub_region')]
        private ?string $subRegion = null,
        #[ORM\Column(name: 'intermediate_region', type: Types::STRING, length: 64, unique: false, nullable: true)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('intermediate_region')]
        private ?string $intermediateRegion = null,
        #[ORM\Column(name: 'region_code', type: Types::STRING, length: 3, unique: false, nullable: true)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('region_code')]
        private ?string $regionCode = null,
        #[ORM\Column(name: 'sub_region_code', type: Types::STRING, length: 3, unique: false, nullable: true)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('sub_region_code')]
        private ?string $subRegionCode = null,
        #[ORM\Column(name: 'intermediate_region_code', type: Types::STRING, length: 3, unique: false, nullable: true)]
        #[Groups([
            CountryGroupModel::LIST_ITEM,
            CountryGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('intermediate_region_code')]
        private ?string $intermediateRegionCode = null,
    ) {
        $this->airports = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid ?? null;
    }

    public function setUuid(?Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt ?? null;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt ?? null;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAlpha2Code(): string
    {
        return $this->alpha2Code;
    }

    public function setAlpha2Code(string $alpha2Code): void
    {
        $this->alpha2Code = $alpha2Code;
    }

    public function getAlpha3Code(): string
    {
        return $this->alpha3Code;
    }

    public function setAlpha3Code(string $alpha3Code): void
    {
        $this->alpha3Code = $alpha3Code;
    }

    public function getNumericCode(): string
    {
        return $this->numericCode;
    }

    public function setNumericCode(string $numericCode): void
    {
        $this->numericCode = $numericCode;
    }

    public function getIso3166Code(): string
    {
        return $this->iso3166Code;
    }

    public function setIso3166Code(string $iso3166Code): void
    {
        $this->iso3166Code = $iso3166Code;
    }

    public function getRegion(): ?string
    {
        return $this->region ?? null;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    public function getSubRegion(): ?string
    {
        return $this->subRegion ?? null;
    }

    public function setSubRegion(?string $subRegion): void
    {
        $this->subRegion = $subRegion;
    }

    public function getIntermediateRegion(): ?string
    {
        return $this->intermediateRegion ?? null;
    }

    public function setIntermediateRegion(?string $intermediateRegion): void
    {
        $this->intermediateRegion = $intermediateRegion;
    }

    public function getRegionCode(): ?string
    {
        return $this->regionCode ?? null;
    }

    public function setRegionCode(?string $regionCode): void
    {
        $this->regionCode = $regionCode;
    }

    public function getSubRegionCode(): ?string
    {
        return $this->subRegionCode ?? null;
    }

    public function setSubRegionCode(?string $subRegionCode): void
    {
        $this->subRegionCode = $subRegionCode;
    }

    public function getIntermediateRegionCode(): ?string
    {
        return $this->intermediateRegionCode ?? null;
    }

    public function setIntermediateRegionCode(?string $intermediateRegionCode): void
    {
        $this->intermediateRegionCode = $intermediateRegionCode;
    }

    public function getAirports(): Collection
    {
        return $this->airports;
    }

    public function setAirports(Collection $airports): void
    {
        $this->airports = $airports;
    }
}
