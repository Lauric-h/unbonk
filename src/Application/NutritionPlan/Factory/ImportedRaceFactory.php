<?php

namespace App\Application\NutritionPlan\Factory;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Entity\CheckpointType;
use App\Domain\NutritionPlan\Entity\ImportedCheckpoint;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\ValueObject\Cutoff;

final readonly class ImportedRaceFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function createFromExternalRace(ExternalRaceDTO $externalRace, string $runnerId): ImportedRace
    {
        $importedRace = new ImportedRace(
            id: $this->idGenerator->generate(),
            runnerId: $runnerId,
            externalRaceId: $externalRace->id,
            externalEventId: $externalRace->eventId,
            eventName: $externalRace->eventName,
            name: $externalRace->name,
            distance: $externalRace->distance,
            ascent: $externalRace->ascent,
            descent: $externalRace->descent,
            startDateTime: $externalRace->startDateTime,
            location: $externalRace->startLocation,
        );

        $startCheckpoint = new ImportedCheckpoint(
            id: $this->idGenerator->generate(),
            externalId: $this->idGenerator->generate(),
            name: 'Start',
            location: $externalRace->startLocation,
            distanceFromStart: 0,
            ascentFromStart: 0,
            descentFromStart: 0,
            cutoff: null,
            assistanceAllowed: false,
            importedRace: $importedRace,
            type: CheckpointType::StartCheckpoint,
        );
        $importedRace->addCheckpoint($startCheckpoint);

        foreach ($externalRace->aidStations as $aidStation) {
            $checkpoint = $this->createCheckpointFromAidStation($aidStation, $importedRace);
            $importedRace->addCheckpoint($checkpoint);
        }

        $finishCheckpoint = new ImportedCheckpoint(
            id: $this->idGenerator->generate(),
            externalId: $this->idGenerator->generate(),
            name: 'Finish',
            location: $externalRace->finishLocation,
            distanceFromStart: $externalRace->distance,
            ascentFromStart: $externalRace->ascent,
            descentFromStart: $externalRace->descent,
            cutoff: null,
            assistanceAllowed: false,
            importedRace: $importedRace,
            type: CheckpointType::FinishCheckpoint,
        );
        $importedRace->addCheckpoint($finishCheckpoint);

        return $importedRace;
    }

    private function createCheckpointFromAidStation(ExternalAidStationDTO $aidStation, ImportedRace $importedRace): ImportedCheckpoint
    {
        $cutoff = null !== $aidStation->cutoffTime
            ? new Cutoff($aidStation->cutoffTime)
            : null;

        return new ImportedCheckpoint(
            id: $this->idGenerator->generate(),
            externalId: $aidStation->id,
            name: $aidStation->name,
            location: $aidStation->location,
            distanceFromStart: $aidStation->distanceFromStart,
            ascentFromStart: $aidStation->ascentFromStart,
            descentFromStart: $aidStation->descentFromStart,
            cutoff: $cutoff,
            assistanceAllowed: $aidStation->assistanceAllowed,
            importedRace: $importedRace,
            type: CheckpointType::AidStation,
        );
    }
}
