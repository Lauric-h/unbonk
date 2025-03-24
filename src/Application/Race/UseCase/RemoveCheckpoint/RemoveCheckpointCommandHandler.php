<?php

namespace App\Application\Race\UseCase\RemoveCheckpoint;

use App\Domain\Race\Repository\CheckpointsCatalog;
use App\Domain\Race\Repository\RacesCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class RemoveCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RacesCatalog $racesCatalog,
        private CheckpointsCatalog $checkpointsCatalog,
    ) {
    }

    public function __invoke(RemoveCheckpointCommand $command): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($command->raceId, $command->runnerId);
        $checkpoint = $this->checkpointsCatalog->getByIdAndRaceId($command->id, $race->id);

        $race->removeCheckpoint($checkpoint);

        $this->racesCatalog->add($race);
    }
}
