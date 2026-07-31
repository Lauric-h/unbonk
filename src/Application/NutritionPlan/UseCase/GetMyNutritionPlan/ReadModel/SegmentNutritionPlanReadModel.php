<?php

namespace App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel;

final class SegmentNutritionPlanReadModel
{
    /**
     * @param NutritionItemReadModel[] $nutritionItems
     */
    public function __construct(
        public string $segmentId,
        public int $order,
        public string $fromCheckpointName,
        public string $toCheckpointName,
        public int $distanceInMeters,
        public int $ascent,
        public int $descent,
        public ?\DateTimeImmutable $cutoffAt,
        public ?int $targetCarbsInGrams,
        public int $totalCarbsInGrams,
        public array $nutritionItems,
    ) {
    }
}