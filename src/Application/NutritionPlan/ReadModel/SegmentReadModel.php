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
        public int $position,
        public CheckpointReadModel $startCheckpoint,
        public CheckpointReadModel $endCheckpoint,
        public int $distance,
        public int $ascent,
        public int $descent,
        public array $nutritionItems = [],
        public int $totalCarbs = 0,
    ) {
    }

    public static function fromSegment(Segment $segment): self
    {
        $nutritionItems = array_map(
            static fn (NutritionItem $nutritionItem) => NutritionItemReadModel::fromNutritionItem($nutritionItem),
            $segment->getNutritionItems()->toArray()
        );

        $totalCarbs = array_reduce(
            $nutritionItems,
            static fn (int $carry, NutritionItemReadModel $item) => $carry + ($item->carbs * $item->quantity),
            0
        );

        return new self(
            id: $segment->id,
            position: $segment->position,
            startCheckpoint: CheckpointReadModel::fromCheckpoint($segment->startCheckpoint),
            endCheckpoint: CheckpointReadModel::fromCheckpoint($segment->endCheckpoint),
            distance: $segment->getDistance()->value,
            ascent: $segment->getAscent()->value,
            descent: $segment->getDescent()->value,
            nutritionItems: $nutritionItems,
            totalCarbs: $totalCarbs,
        );
    }
}
