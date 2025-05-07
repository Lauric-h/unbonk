<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\AidStationCheckpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineCheckpointsCatalog;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\Infrastructure\Shared\Bus\EventBus;
use PHPUnit\Framework\TestCase;

final class UpdateCheckpointCommandHandlerTest extends TestCase
{
    public function testUpdateIntermediateCheckpointUpdatesAllFields(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);

        $handler = new UpdateCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            new MetricsFromStart(120, 10, 1000, 1000),
            $race
        );

        $race->addCheckpoint($checkpoint);

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with('cpId', 'raceId')
            ->willReturn($checkpoint);

        $raceRepository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateCheckpointCommand(
            'cpId',
            'updated',
            'updated',
            CheckpointType::Intermediate,
            100,
            20,
            10,
            10,
            'raceId',
            'runnerId',
        ));

        $this->assertEquals($checkpoint, $race->getCheckpoints()->get(1));
    }

    public function testUpdateAidStationCheckpointUpdatesAllFields(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);

        $handler = new UpdateCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            new MetricsFromStart(120, 10, 1000, 1000),
            $race
        );

        $race->addCheckpoint($checkpoint);

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with('cpId', 'raceId')
            ->willReturn($checkpoint);

        $raceRepository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateCheckpointCommand(
            'cpId',
            'updated',
            'updated',
            CheckpointType::AidStation,
            100,
            20,
            10,
            10,
            'raceId',
            'runnerId',
        ));

        $this->assertEquals($checkpoint, $race->getCheckpoints()->get(1));
    }

    public function testUpdateStartCheckpointUpdatesNameAndLocationOnly(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);

        $handler = new UpdateCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with('cpId', 'raceId')
            ->willReturn($race->getStartCheckpoint());

        $raceRepository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateCheckpointCommand(
            'cpId',
            'updated',
            'updated',
            CheckpointType::Start,
            100,
            20,
            10,
            10,
            'raceId',
            'runnerId',
        ));

        $this->assertSame('updated', $race->getStartCheckpoint()->getName());
        $this->assertSame('updated', $race->getStartCheckpoint()->getLocation());
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->distance);
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->elevationGain);
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->elevationLoss);
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->estimatedTimeInMinutes);
    }

    public function testUpdateFinishCheckpointUpdatesNameAndLocationOnly(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $eventBus = $this->createMock(EventBus::class);

        $handler = new UpdateCheckpointCommandHandler($raceRepository, $checkpointRepository, $eventBus);

        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $raceRepository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $checkpointRepository->expects($this->once())
            ->method('getByIdAndRaceId')
            ->with('cpId', 'raceId')
            ->willReturn($race->getFinishCheckpoint());

        $raceRepository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateCheckpointCommand(
            'cpId',
            'updated',
            'updated',
            CheckpointType::Finish,
            100,
            20,
            10,
            10,
            'raceId',
            'runnerId',
        ));

        $this->assertSame('updated', $race->getFinishCheckpoint()->getName());
        $this->assertSame('updated', $race->getFinishCheckpoint()->getLocation());
        $this->assertSame(42, $race->getFinishCheckpoint()->getMetricsFromStart()->distance);
        $this->assertSame(2000, $race->getFinishCheckpoint()->getMetricsFromStart()->elevationGain);
        $this->assertSame(2000, $race->getFinishCheckpoint()->getMetricsFromStart()->elevationLoss);
        $this->assertSame(360, $race->getFinishCheckpoint()->getMetricsFromStart()->estimatedTimeInMinutes);
    }
}
