<?php

namespace App\Domain\NutritionPlan\Port;

use App\Domain\NutritionPlan\DTO\ExternalNutritionItemDTO;

interface ExternalFoodPort
{
    public function getById(string $id): ExternalNutritionItemDTO;
}
