<?php

namespace App\Application\NutritionPlan\UseCase\ListMyNutritionPlans\ReadModel;

final readonly class NutritionPlanSummaryReadModel
{
    public function __construct(
        public string $id,
        public ?string $name,
        public string $raceName,
        public \DateTimeImmutable $raceStartDateTime,
        public int $totalCarbsInGrams,
    ) {
    }
}