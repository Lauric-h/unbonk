<?php

namespace App\Infrastructure\Race\EventSubscriber;

use App\Domain\Race\Event\CheckpointUpdated;
use App\Domain\Shared\Bus\EventBusInterface;
use App\Infrastructure\Shared\Event\CheckpointUpdatedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PublishUpdatedCheckpointListener
{
    public function __construct(private EventBusInterface $eventBus)
    {
    }

    public function __invoke(CheckpointUpdated $updatedCheckpoint): void
    {
        $this->eventBus->dispatchAfterCurrentBusHasFinished(new CheckpointUpdatedIntegrationEvent($updatedCheckpoint->raceId, $updatedCheckpoint->checkpointId));
    }
}