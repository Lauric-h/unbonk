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
    ) {
    }

    public static function fromSegment(Segment $segment): self
    {
        return new self(
            $segment->id,
            $segment->position,
            CheckpointReadModel::fromCheckpoint($segment->startCheckpoint),
            CheckpointReadModel::fromCheckpoint($segment->endCheckpoint),
            $segment->getDistance()->value,
            $segment->getAscent()->value,
            $segment->getDescent()->value,
            array_map(static fn (NutritionItem $nutritionItem) => NutritionItemReadModel::fromNutritionItem($nutritionItem), $segment->getNutritionItems()->toArray()),
        );
    }
}
