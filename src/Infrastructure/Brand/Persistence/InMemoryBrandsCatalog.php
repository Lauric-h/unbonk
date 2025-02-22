<?php

namespace App\Infrastructure\Brand\Persistence;

use App\Domain\Food\Brand;
use App\Domain\Food\BrandsCatalog;

/**
 * For testing and setup only
 */
final class InMemoryBrandsCatalog implements BrandsCatalog
{
    private array $brands = [];

    public function add(Brand $brand): void
    {
        $this->brands[$brand->id] = $brand;
    }

    public function get(string $id): Brand
    {
        return $this->brands[$id] ?? throw new \Exception('Brand not found');
    }
}