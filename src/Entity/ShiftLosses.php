<?php

namespace App\Entity;

use App\Repository\ShiftLossesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShiftLossesRepository::class)]
class ShiftLosses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 45)]
    private ?string $shift = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'shift_losses', targetEntity: LossDetail::class)]
    private Collection $lossDetails;

    public function __construct()
    {
        $this->lossDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getShift(): ?string
    {
        return $this->shift;
    }

    public function setShift(string $shift): static
    {
        $this->shift = $shift;

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

    /**
     * @return Collection<int, LossDetail>
     */
    public function getLossDetails(): Collection
    {
        return $this->lossDetails;
    }

    public function addLossDetail(LossDetail $lossDetail): static
    {
        if (!$this->lossDetails->contains($lossDetail)) {
            $this->lossDetails->add($lossDetail);
            $lossDetail->setShiftLosses($this);
        }

        return $this;
    }

    public function removeLossDetail(LossDetail $lossDetail): static
    {
        if ($this->lossDetails->removeElement($lossDetail)) {
            // set the owning side to null (unless already changed)
            if ($lossDetail->getShiftLosses() === $this) {
                $lossDetail->setShiftLosses(null);
            }
        }

        return $this;
    }
}
