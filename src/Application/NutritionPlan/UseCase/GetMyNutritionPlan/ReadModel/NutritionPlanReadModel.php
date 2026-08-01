<?php

namespace App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel;

final class NutritionPlanReadModel
{
    /**
     * @param SegmentNutritionPlanReadModel[] $segmentPlans
     */
    public function __construct(
        public string $id,
        public ?string $name,
        public string $raceName,
        public string $eventName,
        public \DateTimeImmutable $raceStartDateTime,
        public int $totalCarbsInGrams,
        public array $segmentPlans,
    ) {
    }
}