<?php

namespace App\Domain\Food\Repository;

use App\Domain\Food\Entity\Food;

interface FoodsCatalog
{
    public function add(Food $food): void;

    public function get(string $id): Food;

    public function remove(string $id): void;

    /**
     * @return Food[]
     */
    public function getAll(): array;
}
