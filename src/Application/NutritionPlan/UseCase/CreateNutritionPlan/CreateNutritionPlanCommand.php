<?php

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;

final readonly class CreateNutritionPlanCommand implements CommandInterface
{
    public function __construct(
        public string $runnerId,
        public string $runnerRaceId,
        public string $nutritionPlanId,
    ) {
    }
}