<?php

namespace App\Application\Race\UseCase\DeleteRace;

use App\Domain\Race\Event\RaceDeleted;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Infrastructure\Shared\Bus\EventBus;

final readonly class DeleteRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog, private EventBus $eventBus)
    {
    }

    public function __invoke(DeleteRaceCommand $command): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($command->id, $command->runnerId);
        $this->racesCatalog->remove($race);

        $this->eventBus->dispatchAfterCurrentBusHasFinished(new RaceDeleted($race->id));
    }
}
