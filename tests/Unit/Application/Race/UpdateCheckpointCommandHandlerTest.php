<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommandHandler;
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

final class UpdateCheckpointCommandHandlerTest extends TestCase
{
    public function testUpdateCheckpointCommand(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'raceId',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runnerId',
        );

        $checkpoint = new Checkpoint(
            'cpId',
            'name',
            'location',
            CheckpointType::Start,
            new MetricsFromStart(120, 120, 5000, 5000),
            $race
        );

        $race->checkpoints->add($checkpoint);

        $raceRepository = $this->createMock(DoctrineRacesCatalog::class);
        $checkpointRepository = $this->createMock(DoctrineCheckpointsCatalog::class);

        $handler = new UpdateCheckpointCommandHandler($raceRepository, $checkpointRepository);

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
            CheckpointType::Start,
            0,
            0,
            0,
            0,
            'raceId',
            'runnerId',
        ));
    }
}
