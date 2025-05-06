<?php

namespace App\Infrastructure\Race\EventSubscriber;

use App\Domain\Race\Event\RaceCheckpointsChanged;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\EventBusInterface;
use App\Infrastructure\Race\DTO\CheckpointDTO;
use App\Infrastructure\Shared\Event\RaceCheckpointsChangedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PublishRaceCheckpointsChangedListener
{
    public function __construct(private EventBusInterface $eventBus, private RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(RaceCheckpointsChanged $checkpointAdded): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($checkpointAdded->raceId, $checkpointAdded->runnerId);

        $checkpoints = [];
        foreach ($race->getCheckpoints() as $checkpoint) {
            $checkpoints[] = CheckpointDTO::fromDomain($checkpoint);
        }

        $this->eventBus->dispatchAfterCurrentBusHasFinished(new RaceCheckpointsChangedIntegrationEvent($race->id, $checkpoints));
    }
}
