<?php

namespace App\UI\Http\Rest\Food\Request;

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
