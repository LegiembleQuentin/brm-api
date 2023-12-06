<?php

namespace App\Filter;

class AbsenceFilter
{
    private ?int $employee = null;
    private ?int $restaurant = null;
    private ?\DateTimeImmutable $date = null;

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
    public function getRestaurant(): ?int
    {
        return $this->restaurant;
    }

    /**
     * @param int|null $restaurant
     */
    public function setRestaurant(?int $restaurant): void
    {
        $this->restaurant = $restaurant;
    }

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


}