<?php

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;

final class NutritionItem
{
    public ?Segment $segment = null;

    public function __construct(
        public string $id,
        public string $externalReference,
        public string $name,
        public Carbs $carbs,
        public Quantity $quantity,
        public ?Calories $calories = null,
    ) {
    }
}
