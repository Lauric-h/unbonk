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
        public string $runnerId,
        public ImportedRaceReadModel $importedRace,
        public array $segments = [],
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        return new self(
            $nutritionPlan->id,
            $nutritionPlan->runnerId,
            ImportedRaceReadModel::fromImportedRace($nutritionPlan->importedRace),
            array_map(static fn (Segment $segment) => SegmentReadModel::fromSegment($segment), $nutritionPlan->getSegments()->toArray()),
        );
    }
}
