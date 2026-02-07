<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Segment;

final readonly class NutritionPlanReadModel
{
    /**
     * @param SegmentReadModel[] $segments
     */
    public function __construct(
        public string $id,
        public ?string $name,
        public string $runnerId,
        public ImportedRaceReadModel $race,
        public array $segments = [],
        public int $totalCarbs = 0,
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        $segments = array_map(
            static fn (Segment $segment) => SegmentReadModel::fromSegment($segment),
            $nutritionPlan->getSegments()->toArray()
        );

        $totalCarbs = array_reduce(
            $segments,
            static fn (int $carry, SegmentReadModel $segment) => $carry + $segment->totalCarbs,
            0
        );

        return new self(
            id: $nutritionPlan->id,
            name: $nutritionPlan->name,
            runnerId: $nutritionPlan->race->runnerId,
            race: ImportedRaceReadModel::fromImportedRace($nutritionPlan->race),
            segments: $segments,
            totalCarbs: $totalCarbs,
        );
    }
}
