<?php

namespace App\Application\NutritionPlan\Port;

use App\Domain\NutritionPlan\DTO\ExternalNutritionItemDTO;

// @TODO RENAME AS CATALOG
interface ExternalFoodPort
{
    public function getById(string $id): ExternalNutritionItemDTO;
}
