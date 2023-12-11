<?php

namespace App\Filter;

use JMS\Serializer\Annotation as Serializer;

class ProductFilter
{
    #[Serializer\Groups(['default'])]
    private ?string $search = null;

    #[Serializer\Groups(['default'])]
    private ?string $category = null;

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
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }
}