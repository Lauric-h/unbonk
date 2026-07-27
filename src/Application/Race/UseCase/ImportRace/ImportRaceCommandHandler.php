<?php

namespace App\Application\Race\UseCase\ImportRace;

use App\Application\Race\Exception\RaceAlreadyImportedException;
use App\Application\Race\Port\RaceCatalogPort;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class ImportRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RaceCatalogPort $raceCatalogPort,
        private RunnerRaceRepositoryInterface $runnerRaceRepository,
        private IdGeneratorInterface $idGenerator,
        private CheckpointFactory $checkpointFactory
    ) {
    }

    public function __invoke(ImportRaceCommand $command): void
    {
        if ($this->runnerRaceRepository->existsForRunner($command->runnerId, $command->raceId)) {
            throw new RaceAlreadyImportedException($command->runnerId, $command->raceId);
        }

        $catalogRace = $this->raceCatalogPort->getRace($command->eventId, $command->raceId);

        $checkpoints = $this->checkpointFactory->createFromCatalogRace($catalogRace);

        $runnerRace = RunnerRace::import(
            id: $this->idGenerator->generate(),
            runnerId: $command->runnerId,
            sourceRaceId: $catalogRace->id,
            eventId: $catalogRace->eventId,
            eventName: $catalogRace->eventName,
            name: $catalogRace->name,
            distance: $catalogRace->distanceInMeters,
            ascent: $catalogRace->ascent,
            descent: $catalogRace->descent,
            startDateTime: $catalogRace->startDateTime,
            location: $catalogRace->startLocation,
            checkpoints: $checkpoints,
            segmentIdGenerator: fn () => $this->idGenerator->generate(),
        );

        $this->runnerRaceRepository->add($runnerRace);
    }
}