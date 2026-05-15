<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;

final readonly class NutritionPlanReadModel
{
    /**
     * @param SegmentNutritionPlanReadModel[] $segmentPlans
     */
    public function __construct(
        public string $id,
        public ?string $name,
        public string $runnerId,
        public RunnerRaceReadModel $runnerRace,
        public array $segmentPlans = [],
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        $segmentPlans = array_map(
            static fn (SegmentNutritionPlan $segmentPlan) => SegmentNutritionPlanReadModel::fromSegmentNutritionPlan($segmentPlan),
            $nutritionPlan->getSegmentPlans()->toArray()
        );

        return new self(
            id: $nutritionPlan->id,
            name: $nutritionPlan->name,
            runnerId: $nutritionPlan->runnerRace->runnerId,
            runnerRace: RunnerRaceReadModel::fromRunnerRace($nutritionPlan->runnerRace),
            segmentPlans: $segmentPlans,
        );
    }
}
