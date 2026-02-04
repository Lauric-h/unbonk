<?php

namespace App\Application\NutritionPlan\UseCase\DeleteUserRace;

use App\Domain\NutritionPlan\Exception\ForbiddenRaceAccessException;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteUserRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RacesCatalog $racesCatalog
    ) {
    }

    public function __invoke(DeleteUserRaceCommand $command): void
    {
        $race = $this->racesCatalog->get($command->raceId);
        if ($race->runnerId !== $command->runnerId) {
            throw new ForbiddenRaceAccessException($command->raceId, $command->runnerId);
        }
        $this->racesCatalog->remove($race);
    }
}
