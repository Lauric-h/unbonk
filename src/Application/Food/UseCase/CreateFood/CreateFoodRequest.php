<?php

namespace App\Application\Food\UseCase\CreateFood;

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
