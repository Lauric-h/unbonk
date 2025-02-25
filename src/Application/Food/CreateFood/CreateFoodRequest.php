<?php

namespace App\Application\Food\CreateFood;

final readonly class CreateFoodRequest
{
    public function __construct(
        public string $name,
        public int $carbs,
        public string $ingestionType,
        public ?int $calories,
    ) {
    }
}
