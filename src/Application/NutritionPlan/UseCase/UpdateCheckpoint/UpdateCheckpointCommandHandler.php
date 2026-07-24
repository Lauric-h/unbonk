<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\UpdateCheckpoint;

use App\Domain\NutritionPlan\Entity\Cutoff;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $runnerRacesCatalog,
    ) {
    }

    public function __invoke(UpdateCheckpointCommand $command): void
    {
        $runnerRace = $this->runnerRacesCatalog->get($command->runnerRaceId);

        $cutoff = null !== $command->cutoffTime
            ? new Cutoff($command->cutoffTime)
            : null;

        $runnerRace->updateCheckpoint(
            checkpointId: $command->checkpointId,
            name: $command->name,
            location: $command->location,
            distanceFromStart: $command->distanceFromStart,
            ascentFromStart: $command->ascentFromStart,
            descentFromStart: $command->descentFromStart,
            cutoff: $cutoff,
            assistanceAllowed: $command->assistanceAllowed,
        );

        $this->runnerRacesCatalog->add($runnerRace);
    }
}
