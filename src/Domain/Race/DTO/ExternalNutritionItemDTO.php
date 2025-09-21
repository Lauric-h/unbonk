<?php

namespace App\Domain\Race\DTO;

readonly class ExternalNutritionItemDTO
{
    public function __construct(
        public string $reference,
        public string $name,
        public int $carbs,
        public ?int $calories = null,
    ) {
    }
}
