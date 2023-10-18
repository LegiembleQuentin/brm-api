<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ApiResource]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $warning = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'feedback')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $author = null;

    #[ORM\ManyToOne(inversedBy: 'concernedFeedback')]
    private ?Employee $employee = null;

    #[ORM\OneToOne(mappedBy: 'feedback', cascade: ['persist', 'remove'])]
    private ?Warning $relatedWarning = null;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isWarning(): ?bool
    {
        return $this->warning;
    }

    public function setWarning(bool $warning): static
    {
        $this->warning = $warning;

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

    public function getAuthor(): ?Employee
    {
        return $this->author;
    }

    public function setAuthor(?Employee $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getRelatedWarning(): ?Warning
    {
        return $this->relatedWarning;
    }

    public function setRelatedWarning(?Warning $relatedWarning): static
    {
        // unset the owning side of the relation if necessary
        if ($relatedWarning === null && $this->relatedWarning !== null) {
            $this->relatedWarning->setFeedback(null);
        }

        // set the owning side of the relation if necessary
        if ($relatedWarning !== null && $relatedWarning->getFeedback() !== $this) {
            $relatedWarning->setFeedback($this);
        }

        $this->relatedWarning = $relatedWarning;

        return $this;
    }
}
