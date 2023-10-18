<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdRepository::class)]
#[ApiResource]
class Ad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $budget = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $target_audience = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $img_url = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: AdCampaign::class)]
    private Collection $adCampaigns;

    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: EmailHistory::class)]
    private Collection $emailHistories;

    #[ORM\OneToOne(mappedBy: 'ad', cascade: ['persist', 'remove'])]
    private ?Promotion $promotion = null;

    public function __construct()
    {
        $this->adCampaigns = new ArrayCollection();
        $this->emailHistories = new ArrayCollection();
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

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(string $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getTargetAudience(): ?string
    {
        return $this->target_audience;
    }

    public function setTargetAudience(?string $target_audience): static
    {
        $this->target_audience = $target_audience;

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
     * @return Collection<int, AdCampaign>
     */
    public function getAdCampaigns(): Collection
    {
        return $this->adCampaigns;
    }

    public function addAdCampaign(AdCampaign $adCampaign): static
    {
        if (!$this->adCampaigns->contains($adCampaign)) {
            $this->adCampaigns->add($adCampaign);
            $adCampaign->setAd($this);
        }

        return $this;
    }

    public function removeAdCampaign(AdCampaign $adCampaign): static
    {
        if ($this->adCampaigns->removeElement($adCampaign)) {
            // set the owning side to null (unless already changed)
            if ($adCampaign->getAd() === $this) {
                $adCampaign->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmailHistory>
     */
    public function getEmailHistories(): Collection
    {
        return $this->emailHistories;
    }

    public function addEmailHistory(EmailHistory $emailHistory): static
    {
        if (!$this->emailHistories->contains($emailHistory)) {
            $this->emailHistories->add($emailHistory);
            $emailHistory->setAd($this);
        }

        return $this;
    }

    public function removeEmailHistory(EmailHistory $emailHistory): static
    {
        if ($this->emailHistories->removeElement($emailHistory)) {
            // set the owning side to null (unless already changed)
            if ($emailHistory->getAd() === $this) {
                $emailHistory->setAd(null);
            }
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): static
    {
        // unset the owning side of the relation if necessary
        if ($promotion === null && $this->promotion !== null) {
            $this->promotion->setAd(null);
        }

        // set the owning side of the relation if necessary
        if ($promotion !== null && $promotion->getAd() !== $this) {
            $promotion->setAd($this);
        }

        $this->promotion = $promotion;

        return $this;
    }
}
