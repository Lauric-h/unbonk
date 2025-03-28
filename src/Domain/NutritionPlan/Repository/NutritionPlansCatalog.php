<?php

namespace App\Domain\NutritionPlan\Repository;

use App\Domain\NutritionPlan\Entity\NutritionPlan;

interface NutritionPlansCatalog
{
    public function add(NutritionPlan $nutritionPlan): void;
}
