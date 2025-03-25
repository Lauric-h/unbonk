<?php

namespace App\Application\Race\UseCase\AddCheckpoint;

use App\Domain\Race\Entity\AidStationCheckpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Repository\RacesCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class AddCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(AddCheckpointCommand $command): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($command->raceId, $command->runnerId);

        $checkpoint = match ($command->checkpointType) {
            CheckpointType::AidStation => new AidStationCheckpoint(
                id: $command->id,
                name: $command->name,
                location: $command->location,
                metricsFromStart: new MetricsFromStart($command->estimatedTimeInMinutes, $command->distance, $command->elevationGain, $command->elevationLoss),
                race: $race
            ),
            CheckpointType::Intermediate => new IntermediateCheckpoint(
                id: $command->id,
                name: $command->name,
                location: $command->location,
                metricsFromStart: new MetricsFromStart($command->estimatedTimeInMinutes, $command->distance, $command->elevationGain, $command->elevationLoss),
                race: $race
            ),
            default => throw new \DomainException('You can only add Intermediate or AidStation checkpoints'),
        };

        $race->addCheckpoint($checkpoint);

        $this->racesCatalog->add($race);
    }
}
