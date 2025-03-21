<?php

namespace App\Domain\Food\Repository;

use App\Domain\Food\Entity\Brand;

interface BrandsCatalog
{
    public function add(Brand $brand): void;

    public function get(string $id): Brand;

    public function remove(Brand $brand): void;

    /**
     * @return Brand[]
     */
    public function getAll(): array;
}
