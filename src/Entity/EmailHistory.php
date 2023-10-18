<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EmailHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailHistoryRepository::class)]
#[ApiResource]
class EmailHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $recipient_email = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sent_date = null;

    #[ORM\ManyToOne(inversedBy: 'emailHistories')]
    private ?Ad $ad = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipientEmail(): ?string
    {
        return $this->recipient_email;
    }

    public function setRecipientEmail(string $recipient_email): static
    {
        $this->recipient_email = $recipient_email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
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

    public function getSentDate(): ?\DateTimeInterface
    {
        return $this->sent_date;
    }

    public function setSentDate(\DateTimeInterface $sent_date): static
    {
        $this->sent_date = $sent_date;

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
