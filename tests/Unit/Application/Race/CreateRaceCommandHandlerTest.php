<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\CreateRace\CreateRaceCommand;
use App\Application\Race\UseCase\CreateRace\CreateRaceCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;

final class CreateRaceCommandHandlerTest extends TestCase
{
    public function testCreateRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);

        $now = new \DateTimeImmutable();
        $command = new CreateRaceCommand(
            id: 'race-id',
            runnerId: 'runner-id',
            date: $now,
            name: 'Le Bélier',
            distance: 42,
            elevationGain: 2000,
            elevationLoss: 2000,
            city: 'La Clusaz',
            postalCode: '74xxx'
        );

        $expected = new Race(
            id: 'race-id',
            date: $now,
            name: 'Le Bélier',
            profile: new Profile(42, 2000, 2000),
            address: new Address('La Clusaz', '74xxx'),
            runnerId: 'runner-id',
        );

        $handler = new CreateRaceCommandHandler($repository);

        $repository->expects($this->once())
            ->method('add')
            ->with($expected);

        ($handler)($command);
    }
}
