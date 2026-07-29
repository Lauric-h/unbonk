<?php

namespace App\Infrastructure\Race\Adapter;

use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Application\Race\UseCase\GetMyRace\ReadModel\CheckpointReadModel;
use App\Application\Race\UseCase\GetMyRace\ReadModel\RunnerRaceDetailReadModel;
use App\Application\Race\UseCase\GetMyRace\ReadModel\SegmentReadModel;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;

final readonly class RunnerRaceReader implements RunnerRaceReaderInterface
{
    public function __construct(private RunnerRaceRepositoryInterface $repository)
    {
    }

    public function get(string $runnerRaceId): RunnerRaceDetailReadModel
    {
        $runnerRace = $this->repository->get($runnerRaceId);

        return $this->toReadModel($runnerRace);
    }

    private function toReadModel(RunnerRace $runnerRace): RunnerRaceDetailReadModel
    {
        $segments = array_map(
            fn ($segment) => new SegmentReadModel(
                id: $segment->id(),
                position: $segment->position(),
                fromCheckpoint: $this->toCheckpointReadModel($segment->fromCheckpoint(), $runnerRace->getStartDateTime()),
                toCheckpoint: $this->toCheckpointReadModel($segment->toCheckpoint(), $runnerRace->getStartDateTime()),
                distanceInMeters: $segment->distanceInMeters(),
                ascent: $segment->ascent(),
                descent: $segment->descent(),
            ),
            $runnerRace->segments(),
        );

        return new RunnerRaceDetailReadModel(
            id: $runnerRace->getId(),
            runnerId: $runnerRace->getRunnerId(),
            name: $runnerRace->getName(),
            eventName: $runnerRace->getEventName(),
            startDateTime: $runnerRace->getStartDateTime(),
            location: $runnerRace->getLocation(),
            distance: $runnerRace->getDistance(),
            ascent: $runnerRace->getAscent(),
            descent: $runnerRace->getDescent(),
            segments: $segments,
        );
    }

    private function toCheckpointReadModel(Checkpoint $checkpoint, \DateTimeImmutable $raceStart): CheckpointReadModel
    {
        return new CheckpointReadModel(
            id: $checkpoint->getId(),
            name: $checkpoint->getName(),
            location: $checkpoint->getLocation(),
            distanceFromStart: $checkpoint->getDistanceFromStart(),
            assistanceAllowed: $checkpoint->isAssistanceAllowed(),
            cutoffAt: $checkpoint->getCutoff()?->absoluteDateTime($raceStart),
        );
    }
}