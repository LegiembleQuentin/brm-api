<?php

namespace App\Filter;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;

class FeedbackFilter
{
    #[Serializer\Groups(['default'])]
    private ?int $employee = null;

    #[Serializer\Groups(['default'])]
    private ?int $author = null;

    #[Serializer\Groups(['default'])]
    private bool $warning = false;

    private ?DateTimeImmutable $date = null;

    /**
     * @return int|null
     */
    public function getEmployee(): ?int
    {
        return $this->employee;
    }

    /**
     * @param int|null $employee
     */
    public function setEmployee(?int $employee): void
    {
        $this->employee = $employee;
    }

    /**
     * @return int|null
     */
    public function getAuthor(): ?int
    {
        return $this->author;
    }

    /**
     * @param int|null $author
     */
    public function setAuthor(?int $author): void
    {
        $this->author = $author;
    }

    /**
     * @return bool
     */
    public function isWarning(): bool
    {
        return $this->warning;
    }

    /**
     * @param bool $warning
     */
    public function setWarning(bool $warning): void
    {
        $this->warning = $warning;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable|null $date
     */
    public function setDate(?DateTimeImmutable $date): void
    {
        $this->date = $date;
    }


}