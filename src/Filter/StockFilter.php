<?php

namespace App\Filter;

use JMS\Serializer\Annotation as Serializer;

class StockFilter
{
    #[Serializer\Groups(['default'])]
    private ?string $search = null;

    #[Serializer\Groups(['default'])]
    private ?int $restaurant = null;

    #[Serializer\Groups(['default'])]
    private bool $alert = false;

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
     * @return bool
     */
    public function isAlert(): bool
    {
        return $this->alert;
    }

    /**
     * @param bool $alert
     */
    public function setAlert(bool $alert): void
    {
        $this->alert = $alert;
    }
}