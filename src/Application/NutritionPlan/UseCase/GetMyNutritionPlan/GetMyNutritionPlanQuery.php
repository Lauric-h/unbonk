<?php

namespace App\Application\NutritionPlan\UseCase\GetMyNutritionPlan;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class GetMyNutritionPlanQuery implements QueryInterface
{
    public function __construct(
        public string $runnerId,
        public string $nutritionPlanId,
    ) {
    }
}