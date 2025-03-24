<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Application\Race\UseCase\RemoveCheckpoint\RemoveCheckpointCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineCheckpointsCatalog;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class RemoveCheckpointCommandHandlerTest extends TestCase
{
    public function testShouldRemoveCheckpoint(): void
    {
        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);
        $handler = new RemoveCheckpointCommandHandler($raceRepository, $checkpointRepository);

        $command = new RemoveCheckpointCommand(
            'id',
            'raceId',
            'runnerId',
        );

        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'raceId',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $start = new Checkpoint(
            'id1',
            'name1',
            'location1',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $finish = new Checkpoint(
            'id3',
            'name3',
            'location3',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $checkpoint = new Checkpoint(
            'id',
            'name1',
            'location1',
            CheckpointType::None,
            new MetricsFromStart(0, 10, 0, 0),
            $race
        );

        $race->addCheckpoint($checkpoint);
        $race->checkpoints->add($start);
        $race->checkpoints->add($finish);
        $race->sortCheckpointByDistance();

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

        ($handler)($command);
    }
}
