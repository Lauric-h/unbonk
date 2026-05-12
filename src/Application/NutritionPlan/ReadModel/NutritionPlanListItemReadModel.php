<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionPlan;

/**
 * Lightweight read model for listing nutrition plans.
 * Contains only essential information for list display.
 */
final readonly class NutritionPlanListItemReadModel
{
    public function __construct(
        public string $id,
        public ?string $nutritionPlanName,
        public string $eventName,
        public string $raceName,
        public int $raceDistance,
        public \DateTimeImmutable $raceDate,
        public int $totalCarbs,
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        // Calculate total carbs from all segments
        $totalCarbs = 0;
        foreach ($nutritionPlan->getSegments() as $segment) {
            foreach ($segment->getNutritionItems() as $nutritionItem) {
                $totalCarbs += $nutritionItem->carbs->value * $nutritionItem->quantity->value;
            }
        }

        return new self(
            id: $nutritionPlan->id,
            nutritionPlanName: $nutritionPlan->name,
            eventName: $nutritionPlan->race->eventName,
            raceName: $nutritionPlan->race->name,
            raceDistance: $nutritionPlan->race->distance,
            raceDate: $nutritionPlan->race->startDateTime,
            totalCarbs: $totalCarbs,
        );
    }
}
