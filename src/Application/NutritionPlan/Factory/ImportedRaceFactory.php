<?php

namespace App\Application\NutritionPlan\Factory;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\ValueObject\Cutoff;
use App\Domain\NutritionPlan\Entity\ImportedRace;

final readonly class ImportedRaceFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function createFromExternalRace(ExternalRaceDTO $externalRace): ImportedRace
    {
        $importedRace = new ImportedRace(
            $this->idGenerator->generate(),
            $externalRace->id,
            $externalRace->eventId,
            $externalRace->name,
            $externalRace->distance,
            $externalRace->ascent,
            $externalRace->descent,
            $externalRace->startDateTime,
            $externalRace->startLocation,
        );

        // Add start checkpoint
        $startCheckpoint = new Checkpoint(
            $this->idGenerator->generate(),
            'start',
            'Start',
            $externalRace->startLocation,
            0,
            0,
            0,
            null,
            false,
            $importedRace,
        );
        $importedRace->addCheckpoint($startCheckpoint);

        // Add aid stations as checkpoints
        foreach ($externalRace->aidStations as $aidStation) {
            $checkpoint = $this->createCheckpointFromAidStation($aidStation, $importedRace);
            $importedRace->addCheckpoint($checkpoint);
        }

        // Add finish checkpoint
        $finishCheckpoint = new Checkpoint(
            $this->idGenerator->generate(),
            'finish',
            'Finish',
            $externalRace->finishLocation,
            $externalRace->distance,
            $externalRace->ascent,
            $externalRace->descent,
            null,
            false,
            $importedRace,
        );
        $importedRace->addCheckpoint($finishCheckpoint);

        return $importedRace;
    }

    private function createCheckpointFromAidStation(ExternalAidStationDTO $aidStation, ImportedRace $importedRace): Checkpoint
    {
        $cutoff = null !== $aidStation->cutoffTime
            ? new Cutoff($aidStation->cutoffTime)
            : null;

        return new Checkpoint(
            $this->idGenerator->generate(),
            $aidStation->id,
            $aidStation->name,
            $aidStation->location,
            $aidStation->distanceFromStart,
            $aidStation->ascentFromStart,
            $aidStation->descentFromStart,
            $cutoff,
            $aidStation->assistanceAllowed,
            $importedRace,
        );
    }
}
