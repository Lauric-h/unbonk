<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\RemoveCheckpoint;

use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class RemoveCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $runnerRacesCatalog,
    ) {
    }

    public function __invoke(RemoveCheckpointCommand $command): void
    {
        $runnerRace = $this->runnerRacesCatalog->get($command->runnerRaceId);

        $runnerRace->removeCheckpoint($command->checkpointId);

        $this->runnerRacesCatalog->add($runnerRace);
    }
}
