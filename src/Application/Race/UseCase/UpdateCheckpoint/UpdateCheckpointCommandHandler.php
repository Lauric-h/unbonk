<?php

namespace App\Application\Race\UseCase\UpdateCheckpoint;

use App\Domain\Race\Entity\AidStationCheckpoint;
use App\Domain\Race\Entity\FinishCheckpoint;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\StartCheckpoint;
use App\Domain\Race\Event\RaceCheckpointsChanged;
use App\Domain\Race\Repository\CheckpointsCatalog;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Infrastructure\Shared\Bus\EventBus;

final readonly class UpdateCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RacesCatalog $racesCatalog,
        private CheckpointsCatalog $checkpointsCatalog,
        private EventBus $eventBus,
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

        $metricsWillChange = false;
        if ($checkpoint instanceof AidStationCheckpoint
            || $checkpoint instanceof IntermediateCheckpoint
        ) {
            $metrics = MetricsFromStart::create(
                new Duration($command->estimatedTimeInMinutes),
                new Distance($command->distance),
                new Ascent($command->elevationGain),
                new Descent($command->elevationLoss)
            );
            $metricsWillChange = $checkpoint->willMetricsChange($metrics);

            $checkpoint->update(
                $command->name,
                $command->location,
                $metrics,
            );
        }

        $race->sortCheckpointByDistance();
        $this->racesCatalog->add($race);

        if (true === $metricsWillChange) {
            $this->eventBus->dispatchAfterCurrentBusHasFinished(new RaceCheckpointsChanged($race->id, $race->runnerId));
        }
    }
}
