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
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        return new self(
            $nutritionPlan->id,
            $nutritionPlan->name,
            $nutritionPlan->race->runnerId,
            ImportedRaceReadModel::fromImportedRace($nutritionPlan->race),
            array_map(static fn (Segment $segment) => SegmentReadModel::fromSegment($segment), $nutritionPlan->getSegments()->toArray()),
        );
    }
}
