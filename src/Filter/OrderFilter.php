<?php

namespace App\Filter;

use JMS\Serializer\Annotation as Serializer;

class OrderFilter
{
    private ?\DateTimeImmutable $date = null;

    #[Serializer\Groups(['default'])]
    private ?int $customer = null;

    #[Serializer\Groups(['default'])]
    private ?string $status = null;

    #[Serializer\Groups(['default'])]
    private ?int $product = null;

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable|null $date
     */
    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string|null
     */
    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    /**
     * @param string|null $customer
     */
    public function setCustomer(?string $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getProduct(): ?string
    {
        return $this->product;
    }

    /**
     * @param string|null $product
     */
    public function setProduct(?string $product): void
    {
        $this->product = $product;
    }




}