<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\CreateRace\CreateRaceCommand;
use App\Application\Race\UseCase\CreateRace\CreateRaceCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Event\RaceCreated;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\Infrastructure\Shared\Bus\EventBus;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class CreateRaceCommandHandlerTest extends TestCase
{
    public function testCreateRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $idGenerator = new MockIdGenerator('id');
        $eventBus = $this->createMock(EventBus::class);

        $date = new \DateTimeImmutable('2025-01-01');
        $command = new CreateRaceCommand(
            id: 'id',
            runnerId: 'runner-id',
            date: $date,
            name: 'Le Bélier',
            distance: 42,
            elevationGain: 2000,
            elevationLoss: 2000,
            city: 'La Clusaz',
            postalCode: '74xxx'
        );

        $expected = Race::create(
            'id',
            $date,
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'id',
            'id'
        );

        $handler = new CreateRaceCommandHandler($repository, $idGenerator, $eventBus);

        $repository->expects($this->once())
            ->method('add')
            ->with($expected);

        $eventBus->expects($this->once())
            ->method('dispatchAfterCurrentBusHasFinished')
            ->with(new RaceCreated($expected->id, $expected->runnerId));

        ($handler)($command);
    }
}
