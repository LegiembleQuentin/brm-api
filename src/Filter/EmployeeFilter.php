<?php

namespace App\Filter;

class EmployeeFilter
{
    private ?string $search = null;
    private ?string $contractType = null;
    private ?int $restaurant = null;
    private ?string $role = null;
    private bool $enabled = false;

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $search
     */
    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    /**
     * @return string|null
     */
    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    /**
     * @param string|null $contractType
     */
    public function setContractType(?string $contractType): void
    {
        $this->contractType = $contractType;
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
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}