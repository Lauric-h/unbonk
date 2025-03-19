<?php

namespace App\Application\Race\UseCase\DeleteRace;

use App\Domain\Race\Repository\RacesCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final class DeleteRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(DeleteRaceCommand $command): void
    {
        $this->racesCatalog->remove($command->id);
    }
}
