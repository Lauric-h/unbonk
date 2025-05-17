<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommand;
use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Event\RaceDeleted;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\Infrastructure\Shared\Bus\EventBus;
use PHPUnit\Framework\TestCase;

final class DeleteRaceCommandHandlerTest extends TestCase
{
    public function testDeleteRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $eventBus = $this->createMock(EventBus::class);
        $handler = new DeleteRaceCommandHandler($repository, $eventBus);
        $command = new DeleteRaceCommand('id', 'runner-id');

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

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('id', 'runner-id')
            ->willReturn($race);

        $repository->expects($this->once())
            ->method('remove')
            ->with($race);

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with(new RaceDeleted($race->id));

        ($handler)($command);
    }
}
