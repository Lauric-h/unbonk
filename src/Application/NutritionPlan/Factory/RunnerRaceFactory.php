<?php

namespace App\Application\NutritionPlan\Factory;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\CheckpointType;
use App\Domain\NutritionPlan\Entity\Cutoff;
use App\Domain\NutritionPlan\Entity\RunnerRace;

final readonly class RunnerRaceFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function createFromExternalRace(ExternalRaceDTO $externalRace, string $runnerId): RunnerRace
    {
        $runnerRace = new RunnerRace(
            id: $this->idGenerator->generate(),
            runnerId: $runnerId,
            sourceRaceId: $externalRace->id,
            eventId: $externalRace->eventId,
            eventName: $externalRace->eventName,
            name: $externalRace->name,
            distance: $externalRace->distance,
            ascent: $externalRace->ascent,
            descent: $externalRace->descent,
            startDateTime: $externalRace->startDateTime,
            location: $externalRace->startLocation,
        );

        // Add start checkpoint
        $startCheckpoint = new Checkpoint(
            id: $this->idGenerator->generate(),
            runnerRace: $runnerRace,
            externalCheckpointId: $this->idGenerator->generate(),
            name: 'Start',
            location: $externalRace->startLocation,
            distanceFromStart: 0,
            ascentFromStart: 0,
            descentFromStart: 0,
            cutoff: null,
            assistanceAllowed: false,
            type: CheckpointType::StartCheckpoint,
        );
        $runnerRace->addCheckpoint($startCheckpoint);

        // Add aid station checkpoints
        foreach ($externalRace->aidStations as $aidStation) {
            $checkpoint = $this->createCheckpointFromAidStation($aidStation, $runnerRace);
            $runnerRace->addCheckpoint($checkpoint);
        }

        // Add finish checkpoint
        $finishCheckpoint = new Checkpoint(
            id: $this->idGenerator->generate(),
            runnerRace: $runnerRace,
            externalCheckpointId: $this->idGenerator->generate(),
            name: 'Finish',
            location: $externalRace->finishLocation,
            distanceFromStart: $externalRace->distance,
            ascentFromStart: $externalRace->ascent,
            descentFromStart: $externalRace->descent,
            cutoff: null,
            assistanceAllowed: false,
            type: CheckpointType::FinishCheckpoint,
        );
        $runnerRace->addCheckpoint($finishCheckpoint);

        return $runnerRace;
    }

    private function createCheckpointFromAidStation(ExternalAidStationDTO $aidStation, RunnerRace $runnerRace): Checkpoint
    {
        $cutoff = null !== $aidStation->cutoffTime
            ? new Cutoff($aidStation->cutoffTime)
            : null;

        return new Checkpoint(
            id: $this->idGenerator->generate(),
            runnerRace: $runnerRace,
            externalCheckpointId: $aidStation->id,
            name: $aidStation->name,
            location: $aidStation->location,
            distanceFromStart: $aidStation->distanceFromStart,
            ascentFromStart: $aidStation->ascentFromStart,
            descentFromStart: $aidStation->descentFromStart,
            cutoff: $cutoff,
            assistanceAllowed: $aidStation->assistanceAllowed,
            type: CheckpointType::AidStation,
        );
    }
}
