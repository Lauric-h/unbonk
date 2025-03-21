<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommand;
use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommandHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteRaceCommandHandlerTest extends TestCase
{
    public function testDeleteRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $handler = new DeleteRaceCommandHandler($repository);
        $command = new DeleteRaceCommand('id', 'runner-id');

        $race = new Race(
            'id',
            new \DateTimeImmutable(),
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('id', 'runner-id')
            ->willReturn($race);

        $repository->expects($this->once())
            ->method('remove')
            ->with($race);

        ($handler)($command);
    }
}
