<?php

namespace App\Application\Race\UseCase\UpdateCheckpoint;

use App\Domain\Race\Entity\AidStationCheckpoint;
use App\Domain\Race\Entity\FinishCheckpoint;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\StartCheckpoint;
use App\Domain\Race\Event\CheckpointAdded;
use App\Domain\Race\Event\CheckpointUpdated;
use App\Domain\Race\Repository\CheckpointsCatalog;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Infrastructure\Shared\Bus\EventBus;

final readonly class UpdateCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RacesCatalog $racesCatalog,
        private CheckpointsCatalog $checkpointsCatalog,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(UpdateCheckpointCommand $command): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($command->raceId, $command->runnerId);
        $checkpoint = $this->checkpointsCatalog->getByIdAndRaceId($command->id, $race->id);

        if ($checkpoint instanceof StartCheckpoint
            || $checkpoint instanceof FinishCheckpoint
        ) {
            $checkpoint->update($command->name, $command->location);
        }

        if ($checkpoint instanceof AidStationCheckpoint
            || $checkpoint instanceof IntermediateCheckpoint
        ) {
            $checkpoint->update(
                $command->name,
                $command->location,
                new MetricsFromStart($command->estimatedTimeInMinutes, $command->distance, $command->elevationGain, $command->elevationLoss),
            );
        }

        $race->sortCheckpointByDistance();
        $this->racesCatalog->add($race);

        // Trigger only if metrics were changed
        $this->eventBus->dispatchAfterCurrentBusHasFinished(new CheckpointUpdated($race->id));
    }
}
