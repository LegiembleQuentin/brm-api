<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'category')]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: PromotionCampaign::class)]
    private Collection $promotionCampaigns;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->promotionCampaigns = new ArrayCollection();
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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PromotionCampaign>
     */
    public function getPromotionCampaigns(): Collection
    {
        return $this->promotionCampaigns;
    }

    public function addPromotionCampaign(PromotionCampaign $promotionCampaign): static
    {
        if (!$this->promotionCampaigns->contains($promotionCampaign)) {
            $this->promotionCampaigns->add($promotionCampaign);
            $promotionCampaign->setCategory($this);
        }

        return $this;
    }

    public function removePromotionCampaign(PromotionCampaign $promotionCampaign): static
    {
        if ($this->promotionCampaigns->removeElement($promotionCampaign)) {
            // set the owning side to null (unless already changed)
            if ($promotionCampaign->getCategory() === $this) {
                $promotionCampaign->setCategory(null);
            }
        }

        return $this;
    }
}
