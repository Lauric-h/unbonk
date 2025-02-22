<?php

namespace App\Domain\Food;

interface BrandsCatalog
{
    public function add(Brand $brand): void;
    public function get(string $id): Brand;
}