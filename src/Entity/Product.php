<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups(['product', 'default'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['product', 'default'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Serializer\Groups(['product', 'default'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Serializer\Groups(['product', 'default'])]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Serializer\Groups(['product'])]
    private ?string $img_url = null;

    #[ORM\Column]
    #[Serializer\Groups(['product'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Serializer\Groups(['product'])]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: LossDetail::class)]
    #[Serializer\Groups(['product'])]
    private Collection $lossDetails;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductStock::class, cascade: ['persist'])]
    #[Serializer\Groups(['product'])]
    private Collection $productStocks;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: PromotionCampaign::class)]
    #[Serializer\Groups(['product'])]
    private Collection $promotionCampaigns;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Serializer\Groups(['product'])]
    #[ORM\JoinTable(name: "product_category")]
    private Collection $category;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderProduct::class)]
    #[Serializer\Groups(['product'])]
    private Collection $orderProducts;


    #[Serializer\Groups(['product'])]
    private Collection $stocks;

    public function __construct()
    {
        $this->lossDetails = new ArrayCollection();
        $this->productStocks = new ArrayCollection();
        $this->promotionCampaigns = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->initStock();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    public function setImgUrl(?string $img_url): static
    {
        $this->img_url = $img_url;

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
            $lossDetail->setProduct($this);
        }

        return $this;
    }

    public function removeLossDetail(LossDetail $lossDetail): static
    {
        if ($this->lossDetails->removeElement($lossDetail)) {
            // set the owning side to null (unless already changed)
            if ($lossDetail->getProduct() === $this) {
                $lossDetail->setProduct(null);
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

    public function addProductStock(ProductStock $productStock): self
    {
        if ($this->productStocks === null) {
            $this->productStocks = new ArrayCollection();
        }

        if (!$this->productStocks->contains($productStock)) {
            $this->productStocks->add($productStock);
            $productStock->setProduct($this);
        }

        return $this;
    }

    public function removeProductStock(ProductStock $productStock): static
    {
        if ($this->productStocks->removeElement($productStock)) {
            // set the owning side to null (unless already changed)
            if ($productStock->getProduct() === $this) {
                $productStock->setProduct(null);
            }
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
            $promotionCampaign->setProduct($this);
        }

        return $this;
    }

    public function removePromotionCampaign(PromotionCampaign $promotionCampaign): static
    {
        if ($this->promotionCampaigns->removeElement($promotionCampaign)) {
            // set the owning side to null (unless already changed)
            if ($promotionCampaign->getProduct() === $this) {
                $promotionCampaign->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                $orderProduct->setProduct(null);
            }
        }

        return $this;
    }

    private function initStock(): void {
        foreach ($this->productStocks as $productStock) {
            $this->stocks[] = $productStock->getStock();
        }
    }

    public function getStocks(): array {
        $stocks = [];
        foreach ($this->productStocks as $productStock) {
            $stocks[] = $productStock->getStock();
        }
        return $stocks;
    }

    public function setStocks(array $stocks): void {
        foreach ($stocks as $stock) {
            $productStock = new ProductStock();
            $productStock->setProduct($this);
            $productStock->setStock($stock);

            $this->productStocks->add($productStock);
        }
    }

    /**
     * @Serializer\PostDeserialize
     */
    public function postDeserialize()
    {
        $this->productStocks = new ArrayCollection();
    }
}
