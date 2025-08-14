<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Event\RaceCheckpointsChanged;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\Infrastructure\Shared\Bus\EventBus;
use PHPUnit\Framework\TestCase;

final class AddCheckpointCommandHandlerTest extends TestCase
{
    public function testAddCheckpointCommand(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new AddCheckpointCommandHandler($repository, $eventBus);
        $command = new AddCheckpointCommand(
            'cpId',
            'name',
            'location',
            CheckpointType::Intermediate,
            120,
            10,
            1000,
            1000,
            'raceId',
            'runnerId'
        );

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
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

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $repository->expects($this->once())
            ->method('add');

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with(new RaceCheckpointsChanged($race->id, $race->runnerId));

        ($handler)($command);

        $this->assertCount(3, $race->getCheckpoints());
        $this->assertEquals($checkpoint, $race->getCheckpoints()->get(1));
    }

    public function testAddStartCheckpointCommandThrowsException(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new AddCheckpointCommandHandler($repository, $eventBus);
        $command = new AddCheckpointCommand(
            'cpId',
            'name',
            'location',
            CheckpointType::Start,
            120,
            10,
            1000,
            1000,
            'raceId',
            'runnerId'
        );

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $repository->expects($this->never())
            ->method('add');

        $eventBus->expects($this->never())
            ->method('dispatchAfterCurrentBusHasFinished');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('You can only add Intermediate or AidStation checkpoints');
        ($handler)($command);
    }

    public function testAddFinishCheckpointCommandThrowsException(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new AddCheckpointCommandHandler($repository, $eventBus);
        $command = new AddCheckpointCommand(
            'cpId',
            'name',
            'location',
            CheckpointType::Finish,
            120,
            10,
            1000,
            1000,
            'raceId',
            'runnerId'
        );

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $repository->expects($this->never())
            ->method('add');

        $eventBus->expects($this->never())
            ->method('dispatchAfterCurrentBusHasFinished');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('You can only add Intermediate or AidStation checkpoints');
        ($handler)($command);
    }
}
