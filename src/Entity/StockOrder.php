<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StockOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockOrderRepository::class)]
#[ApiResource]
class StockOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(length: 45)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expected_delivery_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total_cost = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'stock_order', targetEntity: StockOrderDetail::class)]
    private Collection $stockOrderDetails;

    public function __construct()
    {
        $this->stockOrderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getExpectedDeliveryDate(): ?\DateTimeInterface
    {
        return $this->expected_delivery_date;
    }

    public function setExpectedDeliveryDate(?\DateTimeInterface $expected_delivery_date): static
    {
        $this->expected_delivery_date = $expected_delivery_date;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(?\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getTotalCost(): ?string
    {
        return $this->total_cost;
    }

    public function setTotalCost(string $total_cost): static
    {
        $this->total_cost = $total_cost;

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
            $stockOrderDetail->setStockOrder($this);
        }

        return $this;
    }

    public function removeStockOrderDetail(StockOrderDetail $stockOrderDetail): static
    {
        if ($this->stockOrderDetails->removeElement($stockOrderDetail)) {
            // set the owning side to null (unless already changed)
            if ($stockOrderDetail->getStockOrder() === $this) {
                $stockOrderDetail->setStockOrder(null);
            }
        }

        return $this;
    }
}
