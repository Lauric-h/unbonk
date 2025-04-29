<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Segment;

final class SegmentReadModel
{
    /**
     * @param NutritionItemReadModel[] $nutritionItems
     */
    public function __construct(
        public string $id,
        public string $startId,
        public string $finishId,
        public int $distance,
        public int $ascent,
        public int $descent,
        public int $estimatedTimeInMinutes,
        public int $carbsTarget,
        public array $nutritionItems = [],
    ) {
    }

    public static function fromSegment(Segment $segment): self
    {
        return new self(
            $segment->id,
            $segment->startId,
            $segment->finishId,
            $segment->distance->value,
            $segment->ascent->value,
            $segment->descent->value,
            $segment->estimatedTimeInMinutes->minutes,
            $segment->carbsTarget->value,
            array_map(static fn (NutritionItem $nutritionItem) => NutritionItemReadModel::fromNutritionItem($nutritionItem), $segment->nutritionItems->toArray()),
        );
    }
}
