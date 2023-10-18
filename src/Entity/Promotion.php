<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[ApiResource]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $reduction = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'promotion', targetEntity: PromotionCampaign::class)]
    private Collection $promotionCampaigns;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToOne(inversedBy: 'promotion', cascade: ['persist', 'remove'])]
    private ?Ad $ad = null;

    public function __construct()
    {
        $this->promotionCampaigns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): static
    {
        $this->reduction = $reduction;

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
            $promotionCampaign->setPromotion($this);
        }

        return $this;
    }

    public function removePromotionCampaign(PromotionCampaign $promotionCampaign): static
    {
        if ($this->promotionCampaigns->removeElement($promotionCampaign)) {
            // set the owning side to null (unless already changed)
            if ($promotionCampaign->getPromotion() === $this) {
                $promotionCampaign->setPromotion(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): static
    {
        $this->ad = $ad;

        return $this;
    }
}
