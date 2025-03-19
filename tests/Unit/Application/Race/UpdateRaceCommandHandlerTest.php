<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\UpdateRace\UpdateRaceCommand;
use App\Application\Race\UseCase\UpdateRace\UpdateRaceCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;

final class UpdateRaceCommandHandlerTest extends TestCase
{
    public function testUpdateRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $handler = new UpdateRaceCommandHandler($repository);
        $now = new \DateTimeImmutable('2025-03-19');
        $command = new UpdateRaceCommand(
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

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('race-id', 'runner-id')
            ->willReturn($expected);

        $repository->expects($this->once())
            ->method('add')
            ->with($expected);

        ($handler)($command);
    }
}
