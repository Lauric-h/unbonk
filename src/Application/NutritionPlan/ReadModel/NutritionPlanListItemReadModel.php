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

        // For now, eventName and raceName are the same (race->name)
        // TODO: Store event name in ImportedRace to differentiate event from race
        return new self(
            id: $nutritionPlan->id,
            nutritionPlanName: $nutritionPlan->name,
            eventName: $nutritionPlan->race->name, // TODO: Use actual event name when available
            raceName: $nutritionPlan->race->name,
            raceDistance: $nutritionPlan->race->distance,
            raceDate: $nutritionPlan->race->startDateTime,
            totalCarbs: $totalCarbs,
        );
    }
}
