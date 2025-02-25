<?php

namespace App\Domain\Food\Entity;

class Food
{
    public function __construct(
        public string $id,
        public Brand $brand,
        public string $name,
        public int $carbs,
        public IngestionType $ingestionType,
        public ?int $calories,
    ) {
    }
}
