<?php

namespace App\Application\NutritionPlan\UseCase\DeleteUserRace;

use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteUserRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $racesCatalog
    ) {
    }

    public function __invoke(DeleteUserRaceCommand $command): void
    {
        $race = $this->racesCatalog->get($command->raceId);
        $this->racesCatalog->remove($race);
    }
}
