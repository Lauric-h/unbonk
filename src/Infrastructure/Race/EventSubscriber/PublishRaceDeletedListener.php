<?php

namespace App\Infrastructure\Race\EventSubscriber;

use App\Domain\Race\Event\RaceDeleted;
use App\Infrastructure\Shared\Bus\EventBus;
use App\Infrastructure\Shared\Event\RaceDeletedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PublishRaceDeletedListener
{
    public function __construct(private EventBus $eventBus)
    {
    }

    public function __invoke(RaceDeleted $event): void
    {
        $this->eventBus->dispatchAfterCurrentBusHasFinished(
            new RaceDeletedIntegrationEvent($event->raceId)
        );
    }
}
