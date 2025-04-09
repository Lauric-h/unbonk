<?php

namespace App\UI\Http\Rest\NutritionPlan\Request;

final readonly class AddNutritionItemRequest
{
    public function __construct(
        public string $id,
        public int $quantity,
    ) {
    }
}
