<?php

namespace App\Application\NutritionPlan\UseCase\DeleteUserRace;

use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteRunnerRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $racesCatalog
    ) {
    }

    public function __invoke(DeleteRunnerRaceCommand $command): void
    {
        $race = $this->racesCatalog->get($command->raceId);
        $this->racesCatalog->remove($race);
    }
}
