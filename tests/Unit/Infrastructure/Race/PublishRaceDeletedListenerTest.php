<?php

namespace App\Tests\Unit\Infrastructure\Race;

use App\Domain\Race\Event\RaceDeleted;
use App\Infrastructure\Race\EventSubscriber\PublishRaceDeletedListener;
use App\Infrastructure\Shared\Bus\EventBus;
use App\Infrastructure\Shared\Event\RaceDeletedIntegrationEvent;
use PHPUnit\Framework\TestCase;

final class PublishRaceDeletedListenerTest extends TestCase
{
    public function testDispatchIntegrationEvent(): void
    {
        $eventBus = $this->createMock(EventBus::class);
        $listener = new PublishRaceDeletedListener($eventBus);
        $raceDeleted = new RaceDeleted('raceId');
        $expectedEvent = new RaceDeletedIntegrationEvent($raceDeleted->raceId);

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with($expectedEvent);

        ($listener)($raceDeleted);
    }
}
