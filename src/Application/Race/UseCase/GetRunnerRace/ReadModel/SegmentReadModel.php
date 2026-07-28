<?php

namespace App\Application\Race\UseCase\GetRunnerRace\ReadModel;

final readonly class SegmentReadModel
{
    public function __construct(
        public string $id,
        public int $position,
        public CheckpointReadModel $fromCheckpoint,
        public CheckpointReadModel $toCheckpoint,
        public int $distanceInMeters,
        public int $ascent,
        public int $descent,
    ) {
    }
}
