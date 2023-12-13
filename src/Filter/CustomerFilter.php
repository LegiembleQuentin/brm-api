<?php

namespace App\Filter;

class CustomerFilter
{
    private ?string $search = null;
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
}
