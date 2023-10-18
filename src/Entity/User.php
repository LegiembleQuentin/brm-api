<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column]
    private ?bool $enabled = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $invitationToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $invitationTokenExpiry = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Employee $employee = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserRestaurant::class)]
    private Collection $userRestaurants;

    public function __construct()
    {
        $this->userRestaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string|null
     */
    public function getInvitationToken(): ?string
    {
        return $this->invitationToken;
    }

    /**
     * @param string|null $invitationToken
     */
    public function setInvitationToken(?string $invitationToken): void
    {
        $this->invitationToken = $invitationToken;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getInvitationTokenExpiry(): ?\DateTimeInterface
    {
        return $this->invitationTokenExpiry;
    }

    /**
     * @param \DateTimeInterface|null $invitationTokenExpiry
     */
    public function setInvitationTokenExpiry(?\DateTimeInterface $invitationTokenExpiry): void
    {
        $this->invitationTokenExpiry = $invitationTokenExpiry;
    }

    /**
     * @return string|null
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string|null $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeImmutable|null $created_at
     */
    public function setCreatedAt(?\DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        // unset the owning side of the relation if necessary
        if ($employee === null && $this->employee !== null) {
            $this->employee->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($employee !== null && $employee->getUser() !== $this) {
            $employee->setUser($this);
        }

        $this->employee = $employee;

        return $this;
    }

    /**
     * @return Collection<int, UserRestaurant>
     */
    public function getUserRestaurants(): Collection
    {
        return $this->userRestaurants;
    }

    public function addUserRestaurant(UserRestaurant $userRestaurant): static
    {
        if (!$this->userRestaurants->contains($userRestaurant)) {
            $this->userRestaurants->add($userRestaurant);
            $userRestaurant->setUser($this);
        }

        return $this;
    }

    public function removeUserRestaurant(UserRestaurant $userRestaurant): static
    {
        if ($this->userRestaurants->removeElement($userRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($userRestaurant->getUser() === $this) {
                $userRestaurant->setUser(null);
            }
        }

        return $this;
    }


}
