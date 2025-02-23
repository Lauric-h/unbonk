<?php

namespace App\Domain\Food\Repository;

use App\Domain\Food\Entity\Brand;

interface BrandsCatalog
{
    public function add(Brand $brand): void;

    public function get(string $id): Brand;

    public function remove(string $id): void;
}
