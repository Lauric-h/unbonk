<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Application\Race\UseCase\RemoveCheckpoint\RemoveCheckpointCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\FinishCheckpoint;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Entity\StartCheckpoint;
use App\Domain\Race\Event\RaceCheckpointsChanged;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Infrastructure\Race\Persistence\DoctrineCheckpointsCatalog;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\Infrastructure\Shared\Bus\EventBus;
use PHPUnit\Framework\TestCase;

final class RemoveCheckpointCommandHandlerTest extends TestCase
{
    public function testShouldRemoveCheckpoint(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new RemoveCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $command = new RemoveCheckpointCommand(
            'cpId',
            'raceId',
            'runnerId',
        );

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

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with($command->raceId, $command->runnerId)
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with($command->id, $command->raceId)
            ->willReturn($checkpoint);

        $raceRepository->expects($this->once())
            ->method('add')
            ->with($race);

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with(new RaceCheckpointsChanged($race->id, $race->runnerId));

        ($handler)($command);

        $this->assertCount(2, $race->getCheckpoints());
        $this->assertFalse($race->getCheckpoints()->contains($checkpoint));
    }

    public function testRemoveStartCheckpointThrowsException(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new RemoveCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $command = new RemoveCheckpointCommand(
            'cpId',
            'raceId',
            'runnerId',
        );

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

        $checkpoint = new StartCheckpoint(
            'cpId',
            'name',
            'location',
            $race
        );

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with($command->raceId, $command->runnerId)
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with($command->id, $command->raceId)
            ->willReturn($checkpoint);

        $eventBus->expects($this->never())
            ->method('dispatchAfterCurrentBusHasFinished');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove Start or Finish Checkpoint');

        ($handler)($command);
    }

    public function testRemoveFinishCheckpointThrowsException(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new RemoveCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $command = new RemoveCheckpointCommand(
            'cpId',
            'raceId',
            'runnerId',
        );

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

        $checkpoint = new FinishCheckpoint(
            'cpId',
            'name',
            'location',
            120,
            $race
        );

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with($command->raceId, $command->runnerId)
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with($command->id, $command->raceId)
            ->willReturn($checkpoint);

        $eventBus->expects($this->never())
            ->method('dispatchAfterCurrentBusHasFinished');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove Start or Finish Checkpoint');

        ($handler)($command);
    }
}
