<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Request;

final readonly class CreateNutritionPlanRequest
{
    public function __construct(
        public ?string $name = null,
    ) {
    }
}
