<?php

namespace App\Domain\Race\Port;

use App\Domain\Race\DTO\ExternalNutritionItemDTO;

interface ExternalFoodPort
{
    public function getById(string $id): ExternalNutritionItemDTO;
}
