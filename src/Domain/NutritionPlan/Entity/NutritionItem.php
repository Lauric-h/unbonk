<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Carbs;

class NutritionItem
{
    public function __construct(
        public string $id,
        public SegmentNutritionPlan $segmentNutritionPlan,
        public string $foodItemId,
        public Quantity $quantity,
        public Carbs $carbs,
    ) {
    }
}
