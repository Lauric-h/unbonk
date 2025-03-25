<?php

namespace App\Application\Race\UseCase\RemoveCheckpoint;

use App\Domain\Race\Entity\FinishCheckpoint;
use App\Domain\Race\Entity\StartCheckpoint;
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

        if ($checkpoint instanceof StartCheckpoint
            || $checkpoint instanceof FinishCheckpoint
        ) {
            throw new \DomainException('Cannot remove Start or Finish Checkpoint');
        }

        $race->removeCheckpoint($checkpoint);

        $this->racesCatalog->add($race);
    }
}
