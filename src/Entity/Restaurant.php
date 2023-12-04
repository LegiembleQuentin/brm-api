<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups(['restaurant', 'default'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['restaurant', 'default'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $adress = null;

    #[ORM\Column(length: 10)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $postal_code = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $operating_hours = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    #[Serializer\Groups(['restaurant'])]
    private ?string $rating = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['restaurant'])]
    private ?\DateTimeInterface $open_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['restaurant'])]
    private ?\DateTimeInterface $close_date = null;

    #[ORM\Column]
    #[Serializer\Groups(['restaurant'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Serializer\Groups(['restaurant'])]
    private ?bool $enabled = null;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: UserRestaurant::class)]
    #[Serializer\Groups(['restaurant'])]
    private Collection $userRestaurants;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Employee::class)]
    #[Serializer\Groups(['restaurant'])]
    private Collection $employees;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Stock::class)]
    #[Serializer\Groups(['restaurant'])]
    private Collection $stocks;

    public function __construct()
    {
        $this->userRestaurants = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getOperatingHours(): ?string
    {
        return $this->operating_hours;
    }

    public function setOperatingHours(?string $operating_hours): static
    {
        $this->operating_hours = $operating_hours;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getOpenDate(): ?\DateTimeInterface
    {
        return $this->open_date;
    }

    public function setOpenDate(?\DateTimeInterface $open_date): static
    {
        $this->open_date = $open_date;

        return $this;
    }

    public function getCloseDate(): ?\DateTimeInterface
    {
        return $this->close_date;
    }

    public function setCloseDate(?\DateTimeInterface $close_date): static
    {
        $this->close_date = $close_date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

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
            $userRestaurant->setRestaurant($this);
        }

        return $this;
    }

    public function removeUserRestaurant(UserRestaurant $userRestaurant): static
    {
        if ($this->userRestaurants->removeElement($userRestaurant)) {
            // set the owning side to null (unless already changed)
            if ($userRestaurant->getRestaurant() === $this) {
                $userRestaurant->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setRestaurant($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getRestaurant() === $this) {
                $stock->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setRestaurant($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getRestaurant() === $this) {
                $employee->setRestaurant(null);
            }
        }

        return $this;
    }
}
