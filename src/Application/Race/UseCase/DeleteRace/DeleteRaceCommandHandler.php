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
        $race = $this->racesCatalog->getByIdAndRunnerId($command->id, $command->runnerId);
        $this->racesCatalog->remove($race);
    }
}
