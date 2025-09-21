<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Entity\Segment;

final readonly class NutritionPlanReadModel
{
    public function __construct(
        public string $id,
        public string $raceId,
        public string $runnerId,
        public array $segments = [],
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        return new self(
            $nutritionPlan->id,
            $nutritionPlan->raceId,
            $nutritionPlan->runnerId,
            []
        );
    }
}
