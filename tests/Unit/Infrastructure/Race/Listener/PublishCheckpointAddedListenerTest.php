<?php

namespace App\Tests\Unit\Infrastructure\Race\Listener;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Event\RaceCheckpointsChanged;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\EventBusInterface;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Infrastructure\Race\DTO\CheckpointDTO;
use App\Infrastructure\Race\EventSubscriber\PublishRaceCheckpointsChangedListener;
use App\Infrastructure\Shared\Event\RaceCheckpointsChangedIntegrationEvent;
use PHPUnit\Framework\TestCase;

final class PublishCheckpointAddedListenerTest extends TestCase
{
    public function testPublishCheckpointAdded(): void
    {
        $eventBus = $this->createMock(EventBusInterface::class);
        $repository = $this->createMock(RacesCatalog::class);
        $listener = new PublishRaceCheckpointsChangedListener($eventBus, $repository);

        $raceId = 'raceId';
        $runnerId = 'runnerId';

        $event = new RaceCheckpointsChanged($raceId, $runnerId);

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000)),
            $race
        );
        $race->addCheckpoint($checkpoint);

        $checkpointDTOs = [
            new CheckpointDTO('startId', 'start', 'La Clusaz', 0, 0, 0, 0),
            new CheckpointDTO('cpId', 'name', 'location', 10, 120, 1000, 1000),
            new CheckpointDTO('finishId', 'finish', 'La Clusaz', 42, 360, 2000, 2000),
        ];

        $eventToDispatch = new RaceCheckpointsChangedIntegrationEvent(
            $raceId,
            $checkpointDTOs
        );

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with($raceId, $runnerId)
            ->willReturn($race);

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with($eventToDispatch);

        ($listener)($event);
    }
}
