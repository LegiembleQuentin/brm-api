<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups(['default', 'employee'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Serializer\Groups(['default', 'employee'])]
    private ?string $role = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 5,
    )]
    #[Serializer\Groups(['default', 'employee'])]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    #[Serializer\Groups(['default', 'employee'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    #[Serializer\Groups(['default', 'employee'])]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Serializer\Groups(['employee'])]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Serializer\Groups(['employee'])]
    private ?\DateTimeInterface $hire_date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 9,
        max: 12,
    )]
    #[Serializer\Groups(['employee'])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 1000,
    )]
    #[Serializer\Groups(['employee'])]
    private ?string $address = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 5,
    )]
    #[Serializer\Groups(['employee'])]
    private ?string $postal_code = null;

    #[ORM\Column(length: 45, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 15,
    )]
    #[Serializer\Groups(['employee'])]
    private ?string $social_security_number = null;

    #[ORM\Column(length: 45)]
    #[Assert\Length(
        max: 40,
    )]
    #[Serializer\Groups(['employee'])]
    private ?string $contract_type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['employee'])]
    private ?\DateTimeInterface $contract_end_date = null;

    #[ORM\Column]
    #[Serializer\Groups(['employee'])]
    private ?bool $disability = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 5000,
    )]
    #[Serializer\Groups(['employee'])]
    private ?string $disability_desc = null;

    #[ORM\Column]
    #[Serializer\Groups(['employee'])]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    #[Serializer\Groups(['employee'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Serializer\Groups(['employee'])]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\OneToOne(inversedBy: 'employee', cascade: ['persist', 'remove'])]
    #[Serializer\Groups(['employee'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Feedback::class)]
    #[Serializer\Groups(['employee'])]
    private Collection $feedback;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Feedback::class)]
    #[Serializer\Groups(['employee'])]
    private Collection $concernedFeedback;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Warning::class)]
    #[Serializer\Groups(['employee'])]
    private Collection $warnings;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Absences::class)]
    #[Serializer\Groups(['employee'])]
    private Collection $absences;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: TimeSlot::class)]
    #[Serializer\Groups(['employee'])]
    private Collection $timeSlots;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Groups(['employee'])]
    private ?Restaurant $restaurant = null;
  
    #[ORM\Column(length: 255)]
    #[Assert\Email]
    #[Serializer\Groups(['default', 'employee'])]
    private ?string $email = null;

    public function __construct()
    {
        $this->feedback = new ArrayCollection();
        $this->concernedFeedback = new ArrayCollection();
        $this->warnings = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->timeSlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hire_date;
    }

    public function setHireDate(\DateTimeInterface $hire_date): static
    {
        $this->hire_date = $hire_date;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getSocialSecurityNumber(): ?string
    {
        return $this->social_security_number;
    }

    public function setSocialSecurityNumber(?string $social_security_number): static
    {
        $this->social_security_number = $social_security_number;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contract_type;
    }

    public function setContractType(string $contract_type): static
    {
        $this->contract_type = $contract_type;

        return $this;
    }

    public function getContractEndDate(): ?\DateTimeInterface
    {
        return $this->contract_end_date;
    }

    public function setContractEndDate(?\DateTimeInterface $contract_end_date): static
    {
        $this->contract_end_date = $contract_end_date;

        return $this;
    }

    public function isDisability(): ?bool
    {
        return $this->disability;
    }

    public function setDisability(bool $disability): static
    {
        $this->disability = $disability;

        return $this;
    }

    public function getDisabilityDesc(): ?string
    {
        return $this->disability_desc;
    }

    public function setDisabilityDesc(?string $disability_desc): static
    {
        $this->disability_desc = $disability_desc;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeImmutable $modified_at): static
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setAuthor($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getAuthor() === $this) {
                $feedback->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getConcernedFeedback(): Collection
    {
        return $this->concernedFeedback;
    }

    public function addConcernedFeedback(Feedback $concernedFeedback): static
    {
        if (!$this->concernedFeedback->contains($concernedFeedback)) {
            $this->concernedFeedback->add($concernedFeedback);
            $concernedFeedback->setEmployee($this);
        }

        return $this;
    }

    public function removeConcernedFeedback(Feedback $concernedFeedback): static
    {
        if ($this->concernedFeedback->removeElement($concernedFeedback)) {
            // set the owning side to null (unless already changed)
            if ($concernedFeedback->getEmployee() === $this) {
                $concernedFeedback->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Warning>
     */
    public function getWarnings(): Collection
    {
        return $this->warnings;
    }

    public function addWarning(Warning $warning): static
    {
        if (!$this->warnings->contains($warning)) {
            $this->warnings->add($warning);
            $warning->setEmployee($this);
        }

        return $this;
    }

    public function removeWarning(Warning $warning): static
    {
        if ($this->warnings->removeElement($warning)) {
            // set the owning side to null (unless already changed)
            if ($warning->getEmployee() === $this) {
                $warning->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Absences>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absences $absence): static
    {
        if (!$this->absences->contains($absence)) {
            $this->absences->add($absence);
            $absence->setEmployee($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getEmployee() === $this) {
                $absence->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TimeSlot>
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(TimeSlot $timeSlot): static
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->add($timeSlot);
            $timeSlot->setEmployee($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): static
    {
        if ($this->timeSlots->removeElement($timeSlot)) {
            // set the owning side to null (unless already changed)
            if ($timeSlot->getEmployee() === $this) {
                $timeSlot->setEmployee(null);
            }
        }

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

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
}
