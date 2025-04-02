<?php

namespace App\Domain\Food\DTO;

class FoodDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public int $carbs,
        public ?int $calories = null,
    ) {
    }
}
