<?php

namespace App\Application\Race\UseCase\UpdateCheckpoint;

use App\Domain\Race\Repository\CheckpointsCatalog;
use App\Domain\Race\Repository\RacesCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog, private CheckpointsCatalog $checkpointsCatalog)
    {
    }

    public function __invoke(UpdateCheckpointCommand $command): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($command->raceId, $command->runnerId);
        $checkpoint = $this->checkpointsCatalog->getByIdAndRaceId($command->id, $race->id);

        $checkpoint->update(
            $command->name,
            $command->location,
            $command->checkpointType,
            $command->estimatedTimeInMinutes,
            $command->distance,
            $command->elevationGain,
            $command->elevationLoss
        );

        $this->checkpointsCatalog->add($checkpoint);
    }
}
