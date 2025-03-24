<?php

namespace App\Application\Race\UseCase\AddCheckpoint;

use App\Domain\Race\Entity\Checkpoint;
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

        $race->addCheckpoint(new Checkpoint(
            id: $command->id,
            name: $command->name,
            location: $command->location,
            checkpointType: $command->checkpointType,
            metricsFromStart: new MetricsFromStart($command->estimatedTimeInMinutes, $command->distance, $command->elevationGain, $command->elevationLoss),
            race: $race
        ));

        $this->racesCatalog->add($race);
    }
}
