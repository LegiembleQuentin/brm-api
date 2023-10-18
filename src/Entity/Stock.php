<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
#[ApiResource]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $quantity = null;

    #[ORM\Column(length: 45)]
    private ?string $unit = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_restock_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2, nullable: true)]
    private ?string $stock_level_alert = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: LossDetail::class)]
    private Collection $lossDetails;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: ProductStock::class)]
    private Collection $productStocks;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: StockOrderDetail::class)]
    private Collection $stockOrderDetails;

    public function __construct()
    {
        $this->lossDetails = new ArrayCollection();
        $this->productStocks = new ArrayCollection();
        $this->stockOrderDetails = new ArrayCollection();
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

    public function getLastRestockDate(): ?\DateTimeInterface
    {
        return $this->last_restock_date;
    }

    public function setLastRestockDate(?\DateTimeInterface $last_restock_date): static
    {
        $this->last_restock_date = $last_restock_date;

        return $this;
    }

    public function getStockLevelAlert(): ?string
    {
        return $this->stock_level_alert;
    }

    public function setStockLevelAlert(?string $stock_level_alert): static
    {
        $this->stock_level_alert = $stock_level_alert;

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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

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
            $lossDetail->setStock($this);
        }

        return $this;
    }

    public function removeLossDetail(LossDetail $lossDetail): static
    {
        if ($this->lossDetails->removeElement($lossDetail)) {
            // set the owning side to null (unless already changed)
            if ($lossDetail->getStock() === $this) {
                $lossDetail->setStock(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductStock>
     */
    public function getProductStocks(): Collection
    {
        return $this->productStocks;
    }

    public function addProductStock(ProductStock $productStock): static
    {
        if (!$this->productStocks->contains($productStock)) {
            $this->productStocks->add($productStock);
            $productStock->setStock($this);
        }

        return $this;
    }

    public function removeProductStock(ProductStock $productStock): static
    {
        if ($this->productStocks->removeElement($productStock)) {
            // set the owning side to null (unless already changed)
            if ($productStock->getStock() === $this) {
                $productStock->setStock(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StockOrderDetail>
     */
    public function getStockOrderDetails(): Collection
    {
        return $this->stockOrderDetails;
    }

    public function addStockOrderDetail(StockOrderDetail $stockOrderDetail): static
    {
        if (!$this->stockOrderDetails->contains($stockOrderDetail)) {
            $this->stockOrderDetails->add($stockOrderDetail);
            $stockOrderDetail->setStock($this);
        }

        return $this;
    }

    public function removeStockOrderDetail(StockOrderDetail $stockOrderDetail): static
    {
        if ($this->stockOrderDetails->removeElement($stockOrderDetail)) {
            // set the owning side to null (unless already changed)
            if ($stockOrderDetail->getStock() === $this) {
                $stockOrderDetail->setStock(null);
            }
        }

        return $this;
    }
}
