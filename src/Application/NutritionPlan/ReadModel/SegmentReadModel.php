<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Segment;

final class SegmentReadModel
{
    public function __construct(
        public string              $id,
        public int                 $position,
        public CheckpointReadModel $fromCheckpoint,
        public CheckpointReadModel $toCheckpoint,
        public int                 $distance,
        public int                 $ascent,
        public int                 $descent,
    ) {
    }

    public static function fromSegment(Segment $segment): self
    {
        return new self(
            id: $segment->id,
            position: $segment->position,
            fromCheckpoint: CheckpointReadModel::fromCheckpoint($segment->fromCheckpoint),
            toCheckpoint: CheckpointReadModel::fromCheckpoint($segment->toCheckpoint),
            distance: $segment->getDistance()->value,
            ascent: $segment->getAscent()->value,
            descent: $segment->getDescent()->value,
        );
    }
}
