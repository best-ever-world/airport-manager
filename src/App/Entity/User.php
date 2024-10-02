<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\App\Entity;

use BestEverWorld\AirportManager\Api\Model\AirportGroupModel;
use BestEverWorld\AirportManager\Api\Model\UserGroupModel;
use BestEverWorld\AirportManager\App\Model\UserRoleModel;
use BestEverWorld\AirportManager\App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: '`user`')]
/**
 * Do we really needs in such scope of indices? Let's assume we are going
 * to operate by some super-pooper-big-heavy-load system, and it will be strongly mandatory
 * to have big scope of indices... :-).
 */
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A0', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A1', columns: ['updated_at'])]
#[ORM\Index(name: 'IDX_75EA56E0E3BD61A2', columns: ['username'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UpdatedAtAwareInterface, UuidAwareInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups([
        UserGroupModel::VIEW_LIST,
        UserGroupModel::VIEW_ITEM,
        AirportGroupModel::LIST_ITEM,
        AirportGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[Groups([
        UserGroupModel::VIEW_LIST,
        UserGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIMETZ_IMMUTABLE, unique: false, nullable: true)]
    #[Groups([
        UserGroupModel::VIEW_LIST,
        UserGroupModel::VIEW_ITEM,
    ])]
    #[SerializedName('updated_at')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: Airport::class, mappedBy: 'created_by', fetch: 'EXTRA_LAZY')]
    private Collection $createdAirports;
    #[ORM\OneToMany(targetEntity: Airport::class, mappedBy: 'updated_by', fetch: 'EXTRA_LAZY')]
    private Collection $updatedAirports;
    #[ORM\OneToMany(targetEntity: UserToken::class, mappedBy: 'user', fetch: 'EXTRA_LAZY')]
    private Collection $securityTokens;

    public function __construct(
        #[ORM\Column(name: 'first_name', type: Types::STRING, length: 64, unique: false, nullable: false)]
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('first_name')]
        private string $firstName,
        #[ORM\Column(name: 'last_name', type: Types::STRING, length: 64, unique: false, nullable: false)]
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('last_name')]
        private string $lastName,
        #[ORM\Column(name: 'username', type: Types::STRING, length: 128, unique: true, nullable: false)]
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
            AirportGroupModel::LIST_ITEM,
            AirportGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('username')]
        private string $username,
        #[ORM\Column(name: 'password', type: Types::STRING, length: 128, unique: false, nullable: false)]
        private string $password,
        /**
         * @var array<string>
         */
        #[ORM\Column(name: 'roles', type: Types::JSON, nullable: false)]
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('roles')]
        private array $roles = [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
        #[ORM\Column(name: 'approved', type: Types::BOOLEAN, nullable: false)]
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('approved')]
        private bool $approved = false,
        #[ORM\Column(name: 'disabled', type: Types::BOOLEAN, nullable: false)]
        #[Groups([
            UserGroupModel::VIEW_LIST,
            UserGroupModel::VIEW_ITEM,
        ])]
        #[SerializedName('disabled')]
        private bool $disabled = false,
    ) {
        $this->createdAirports = new ArrayCollection();
        $this->updatedAirports = new ArrayCollection();
        $this->securityTokens = new ArrayCollection();
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(?Uuid $uuid): User
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAirports(): Collection
    {
        return $this->createdAirports;
    }

    public function setCreatedAirports(Collection $createdAirports): User
    {
        $this->createdAirports = $createdAirports;

        return $this;
    }

    public function getUpdatedAirports(): Collection
    {
        return $this->updatedAirports;
    }

    public function setUpdatedAirports(Collection $updatedAirports): User
    {
        $this->updatedAirports = $updatedAirports;

        return $this;
    }

    public function getSecurityTokens(): Collection
    {
        return $this->securityTokens;
    }

    public function setSecurityTokens(Collection $securityTokens): User
    {
        $this->securityTokens = $securityTokens;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): User
    {
        $this->approved = $approved;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): User
    {
        $this->disabled = $disabled;

        return $this;
    }
}
