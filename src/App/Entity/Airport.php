<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use BestEverWorld\AirportManager\App\Repository\AirportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'airport')]
/**
 * Do we really needs in such scope of indices? Let's assume we are going
 * to operate some super-pooper system, and it will be strongly mandatory
 * to have big scope of indices...
 */
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A0', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A1', columns: ['updated_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A2', columns: ['code'])]
#[ORM\Entity(repositoryClass: AirportRepository::class)]
class Airport implements UpdatedAtAwareInterface, UuidAwareInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups([
        AirportGroupModel::LIST_ITEM,
        AirportGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[Groups([
        AirportGroupModel::LIST_ITEM,
        AirportGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[Groups([
        AirportGroupModel::LIST_ITEM,
        AirportGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('updated_at')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct(
        #[ORM\Column(name: 'name', type: Types::STRING, length: 128, unique: false, nullable: false)]
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('name')]
        private string $name,
        #[ORM\Column(name: 'code', type: Types::STRING, length: 3, unique: true, nullable: false)]
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('code')]
        private string $code,
        #[ORM\Column(name: 'city', type: Types::STRING, length: 128, unique: false, nullable: false)]
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('city')]
        private string $city,
        #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'airports')]
        #[ORM\JoinColumn(name: 'country', referencedColumnName: 'uuid')]
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('country')]
        private ?Country $country = null,
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'createdAirports')]
        #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'uuid')]
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('created_by')]
        private ?User $createdBy = null,
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'updatedAirports')]
        #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'uuid')]
        #[Groups([
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('updated_by')]
        private ?User $updatedBy = null,
    ) {
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(?Uuid $uuid): Airport
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): Airport
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Airport
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Airport
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): Airport
    {
        $this->code = $code;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): Airport
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): Airport
    {
        $this->country = $country;
        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): Airport
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): Airport
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}
