<?php

namespace App\Infrastructure\Race\EventSubscriber;

use App\Domain\Race\Event\CheckpointAdded;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\EventBusInterface;
use App\Infrastructure\Race\DTO\CheckpointDTO;
use App\Infrastructure\Shared\Event\CheckpointAddedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PublishCheckpointAddedListener
{
    public function __construct(private EventBusInterface $eventBus, private RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(CheckpointAdded $checkpointAdded): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($checkpointAdded->raceId, $checkpointAdded->runnerId);

        $checkpoints = [];
        foreach ($race->getCheckpoints() as $checkpoint) {
            $checkpoints[] = CheckpointDTO::fromDomain($checkpoint);
        }

        $this->eventBus->dispatchAfterCurrentBusHasFinished(new CheckpointAddedIntegrationEvent($race->id, $checkpoints));
    }
}
