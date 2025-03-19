<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommand;
use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommandHandler;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteRaceCommandHandlerTest extends TestCase
{
    public function testDeleteRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $handler = new DeleteRaceCommandHandler($repository);
        $command = new DeleteRaceCommand('id');

        $repository->expects($this->once())
            ->method('remove')
            ->with('id');

        ($handler)($command);
    }
}
