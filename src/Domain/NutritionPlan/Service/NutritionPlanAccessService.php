<?php

namespace App\Domain\NutritionPlan\Service;

use App\Domain\NutritionPlan\Exception\ForbiddenNutritionPlanAccessException;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;

final readonly class NutritionPlanAccessService
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
    ) {
    }

    public function checkAccess(string $nutritionPlanId, string $runnerId): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($nutritionPlanId);
        if ($nutritionPlan->race->runnerId !== $runnerId) {
            throw new ForbiddenNutritionPlanAccessException($nutritionPlanId, $runnerId);
        }
    }
}
