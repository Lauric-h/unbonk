<?php

namespace App\Application\Race\UseCase\ImportRace;

use App\Application\Race\ReadModel\CatalogAidStationReadModel;
use App\Application\Race\ReadModel\CatalogRaceReadModel;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\Cutoff;

final readonly class CheckpointFactory
{

    public function __construct(private IdGeneratorInterface $idGenerator)
    {
    }

    public function createFromCatalogRace(CatalogRaceReadModel $catalogRaceReadModel): array
    {
        $checkpoints = [];

        $checkpoints[] = $this->createStart($catalogRaceReadModel);

        foreach ($catalogRaceReadModel->aidStations as $aidStation) {
            $checkpoints[] = $this->createIntermediate($aidStation);
        }

        $checkpoints[] = $this->createFinish($catalogRaceReadModel);

        return $checkpoints;
    }

    private function createStart(CatalogRaceReadModel $catalogRaceReadModel): Checkpoint
    {
        return new Checkpoint(
            id: $this->idGenerator->generate(),
            name: 'Départ',
            location: $catalogRaceReadModel->startLocation,
            distanceFromStart: 0,
            ascentFromStart: 0,
            descentFromStart: 0,
            cutoff: null,
            assistanceAllowed: true,
            type: CheckpointType::Start,
        );
    }

    private function createFinish(CatalogRaceReadModel $catalogRaceReadModel): Checkpoint
    {
        return new Checkpoint(
            id: $this->idGenerator->generate(),
            name: 'Arrivée',
            location: $catalogRaceReadModel->finishLocation,
            distanceFromStart: $catalogRaceReadModel->distanceInMeters,
            ascentFromStart: $catalogRaceReadModel->ascent,
            descentFromStart: $catalogRaceReadModel->descent,
            cutoff: new Cutoff($catalogRaceReadModel->maxDurationInMinutes),
            assistanceAllowed: true,
            type: CheckpointType::Finish,
        );
    }

    private function createIntermediate(CatalogAidStationReadModel $catalogAidStationReadModel): Checkpoint
    {
        return new Checkpoint(
            id: $this->idGenerator->generate(),
            name: $catalogAidStationReadModel->name,
            location: $catalogAidStationReadModel->location,
            distanceFromStart: $catalogAidStationReadModel->distanceFromStartInMeters,
            ascentFromStart: $catalogAidStationReadModel->ascentFromStart,
            descentFromStart: $catalogAidStationReadModel->descentFromStart,
            cutoff: new Cutoff($catalogAidStationReadModel->cutoffOffsetInMinutes),
            assistanceAllowed: $catalogAidStationReadModel->assistanceAllowed,
            type: CheckpointType::Intermediate,
        );
    }
}