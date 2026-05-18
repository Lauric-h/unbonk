<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Domain\NutritionPlan\Entity\Segment;

final readonly class RunnerRaceReadModel
{
    /**
     * @param CheckpointReadModel[] $checkpoints
     * @param SegmentReadModel[] $segments
     */
    public function __construct(
        public string $id,
        public string $externalRaceId,
        public string $externalEventId,
        public string $name,
        public int $distance,
        public int $ascent,
        public int $descent,
        public \DateTimeImmutable $startDateTime,
        public string $location,
        public array $checkpoints = [],
        public array $segments = [],
    ) {
    }

    public static function fromRunnerRace(RunnerRace $runnerRace): self
    {
        return new self(
            id: $runnerRace->id,
            externalRaceId: $runnerRace->sourceRaceId,
            externalEventId: $runnerRace->eventId,
            name: $runnerRace->name,
            distance: $runnerRace->distance,
            ascent: $runnerRace->ascent,
            descent: $runnerRace->descent,
            startDateTime: $runnerRace->startDateTime,
            location: $runnerRace->location,
            checkpoints: array_map(
                static fn (Checkpoint $checkpoint) => CheckpointReadModel::fromCheckpoint($checkpoint),
                $runnerRace->orderedCheckpoints()
            ),
            segments: array_map(
                static fn (Segment $segment) => SegmentReadModel::fromSegment($segment),
                $runnerRace->segments()->toArray(),
            )
        );
    }
}
