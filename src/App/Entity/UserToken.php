<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

use BestEverWorld\AirportManager\App\Repository\UserTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'user_token')]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61B0', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61B1', columns: ['updated_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61b2', columns: ['identifier'])]
#[ORM\Entity(repositoryClass: UserTokenRepository::class)]
class UserToken implements UpdatedAtAwareInterface, UuidAwareInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[SerializedName('uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[SerializedName('created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[SerializedName('updated_at')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'securityTokens')]
        #[ORM\JoinColumn(name: 'user', referencedColumnName: 'uuid')]
        #[SerializedName('user')]
        private User $user,
        #[SerializedName('identifier')]
        #[ORM\Column(name: 'identifier', type: UuidType::NAME, unique: true, nullable: false)]
        private string $identifier,
    ) {
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(?Uuid $uuid): UserToken
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): UserToken
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): UserToken
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserToken
    {
        $this->user = $user;
        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): UserToken
    {
        $this->identifier = $identifier;
        return $this;
    }
}
