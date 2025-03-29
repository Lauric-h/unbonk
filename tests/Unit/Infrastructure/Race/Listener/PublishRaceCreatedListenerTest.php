<?php

namespace App\Tests\Unit\Infrastructure\Race\Listener;

use App\Domain\Race\Event\RaceCreated;
use App\Infrastructure\Race\EventSubscriber\PublishRaceCreatedListener;
use App\Infrastructure\Shared\Bus\EventBus;
use App\Infrastructure\Shared\Event\RaceCreatedIntegrationEvent;
use PHPUnit\Framework\TestCase;

final class PublishRaceCreatedListenerTest extends TestCase
{
    public function testDispatchRaceCreated(): void
    {
        $eventBus = $this->createMock(EventBus::class);
        $raceId = 'raceId';
        $runnerId = 'runnerId';

        $raceCreated = new RaceCreated($raceId, $runnerId);

        $expected = new RaceCreatedIntegrationEvent($raceId, $runnerId);

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with($expected);

        $listener = new PublishRaceCreatedListener($eventBus);

        ($listener)($raceCreated);
    }
}
