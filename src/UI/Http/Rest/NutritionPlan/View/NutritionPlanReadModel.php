<?php

namespace App\UI\Http\Rest\NutritionPlan\View;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Segment;

final readonly class NutritionPlanReadModel
{
    /**
     * @param SegmentReadModel[] $segments
     */
    public function __construct(
        public string $id,
        public string $raceId,
        public string $runnerId,
        public array $segments = [],
    ) {
    }

    public static function fromNutritionPlan(NutritionPlan $nutritionPlan): self
    {
        return new self(
            $nutritionPlan->id,
            $nutritionPlan->raceId,
            $nutritionPlan->runnerId,
            array_map(static fn (Segment $segment) => SegmentReadModel::fromSegment($segment), $nutritionPlan->segments->toArray()),
        );
    }
}
