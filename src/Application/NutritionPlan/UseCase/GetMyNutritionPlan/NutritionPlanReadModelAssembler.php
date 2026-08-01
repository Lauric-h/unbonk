<?php

namespace App\Application\NutritionPlan\UseCase\GetMyNutritionPlan;

use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel\NutritionItemReadModel;
use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel\SegmentNutritionPlanReadModel;
use App\Application\Race\UseCase\GetMyRace\ReadModel\RunnerRaceDetailReadModel;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;

final class NutritionPlanReadModelAssembler
{
    public function assemble(NutritionPlan $plan, RunnerRaceDetailReadModel $race): NutritionPlanReadModel
    {
        $segmentsById = [];
        foreach ($race->segments as $segment) {
            $segmentsById[$segment->id] = $segment;
        }

        return new NutritionPlanReadModel(
            id: $plan->getId(),
            name: $plan->getName(),
            raceName: $race->name,
            eventName: $race->eventName,
            raceStartDateTime: $race->startDateTime,
            totalCarbsInGrams: $plan->totalCarbs()->grams,
            segmentPlans: array_map(
                fn (SegmentNutritionPlan $segmentPlan) => $this->assembleSegment($segmentPlan, $segmentsById),
                $plan->getSegmentPlans(),
            ),
        );
    }

    /**
     * @param array<string, object> $segmentsById
     */
    private function assembleSegment(SegmentNutritionPlan $segmentPlan, array $segmentsById): SegmentNutritionPlanReadModel
    {
        $segment = $segmentsById[$segmentPlan->getSegmentId()]
            ?? throw new \RuntimeException(sprintf(
                'Segment "%s" referenced by nutrition plan not found in race.',
                $segmentPlan->getSegmentId(),
            ));

        return new SegmentNutritionPlanReadModel(
            segmentId: $segment->id,
            position: $segmentPlan->getPosition(),
            fromCheckpointName: $segment->fromCheckpoint->name,
            toCheckpointName: $segment->toCheckpoint->name,
            distanceInMeters: $segment->distanceInMeters,
            ascent: $segment->ascent,
            descent: $segment->descent,
            cutoffAt: $segment->toCheckpoint->cutoffAt,
            targetCarbsInGrams: $segmentPlan->getTargetCarbs()?->grams,
            totalCarbsInGrams: $segmentPlan->totalCarbs()->grams,
            nutritionItems: array_map(
                static fn (NutritionItem $item) => new NutritionItemReadModel(
                    id: $item->getId(),
                    name: $item->getName(),
                    quantity: $item->getQuantity(),
                    totalCarbsInGrams: $item->totalCarbs()->grams,
                ),
                $segmentPlan->getNutritionItems(),
            ),
        );
    }
}