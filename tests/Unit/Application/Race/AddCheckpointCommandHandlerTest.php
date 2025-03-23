<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class AddCheckpointCommandHandlerTest extends TestCase
{
    public function testAddCheckpointCommand(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $handler = new AddCheckpointCommandHandler($repository);
        $command = new AddCheckpointCommand(
            'cpId',
            'name',
            'location',
            CheckpointType::Start,
            120,
            120,
            5000,
            5000,
            'raceId',
            'runnerId'
        );

        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'raceId',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runnerId',
        );

        $raceWithCheckpoint = $date = new DatePoint('2025-03-19');
        $raceWithCheckpoint = new Race(
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
            $raceWithCheckpoint
        );

        $raceWithCheckpoint->checkpoints->add($checkpoint);

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('raceId', 'runnerId')
            ->willReturn($race);

        $repository->expects($this->once())
            ->method('add')
            ->with($raceWithCheckpoint);

        ($handler)($command);
    }
}
