<?php

namespace App\Entity;

use App\Repository\LossDetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LossDetailRepository::class)]
class LossDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $quantity = null;

    #[ORM\Column(length: 45)]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'lossDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShiftLosses $shift_losses = null;

    #[ORM\ManyToOne(inversedBy: 'lossDetails')]
    private ?Product $product = null;

    #[ORM\ManyToOne(cascade: ['remove'], inversedBy: 'lossDetails')]
    private ?Stock $stock = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getShiftLosses(): ?ShiftLosses
    {
        return $this->shift_losses;
    }

    public function setShiftLosses(?ShiftLosses $shift_losses): static
    {
        $this->shift_losses = $shift_losses;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): static
    {
        $this->stock = $stock;

        return $this;
    }
}
