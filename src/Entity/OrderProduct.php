<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups(['default'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Serializer\Groups(['default'])]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Serializer\Groups(['default'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $associated_order = null;

    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Groups(['order'])]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    private ?PromotionCampaign $promotion_campaign = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
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

    public function getAssociatedOrder(): ?Order
    {
        return $this->associated_order;
    }

    public function setAssociatedOrder(?Order $associated_order): static
    {
        $this->associated_order = $associated_order;

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

    public function getPromotionCampaign(): ?PromotionCampaign
    {
        return $this->promotion_campaign;
    }

    public function setPromotionCampaign(?PromotionCampaign $promotion_campaign): static
    {
        $this->promotion_campaign = $promotion_campaign;

        return $this;
    }
}
