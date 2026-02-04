<?php

namespace App\Domain\NutritionPlan\Repository;

use App\Domain\NutritionPlan\Entity\NutritionPlan;

interface NutritionPlansCatalog
{
    public function add(NutritionPlan $nutritionPlan): void;

    public function remove(NutritionPlan $nutritionPlan): void;

    public function get(string $id): NutritionPlan;

    /**
     * @return NutritionPlan[]
     */
    public function findByRaceId(string $raceId): array;
}
