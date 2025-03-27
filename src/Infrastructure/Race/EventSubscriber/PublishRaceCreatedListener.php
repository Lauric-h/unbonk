<?php

namespace App\Infrastructure\Race\EventSubscriber;

use App\Domain\Race\Event\RaceCreated;
use App\Infrastructure\Shared\Bus\EventBus;
use App\Infrastructure\Shared\Event\RaceCreatedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PublishRaceCreatedListener
{
    public function __construct(
        private EventBus $eventBus,
    ) {
    }

    public function __invoke(RaceCreated $event): void
    {
        $this->eventBus->dispatchAfterCurrentBusHasFinished(new RaceCreatedIntegrationEvent(
            $event->id,
            $event->runnerId,
        ));
    }
}
