<?php

namespace App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel;

final class NutritionItemReadModel
{
    public function __construct(
        public string $id,
        public string $name,
        public int $quantity,
        public int $totalCarbsInGrams,
    ) {
    }
}