<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\AddCheckpoint;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\Cutoff;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class AddCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $runnerRacesCatalog,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(AddCheckpointCommand $command): void
    {
        $runnerRace = $this->runnerRacesCatalog->get($command->runnerRaceId);

        $cutoff = null !== $command->cutoffTime
            ? new Cutoff($command->cutoffTime)
            : null;

        $checkpoint = new Checkpoint(
            id: $this->idGenerator->generate(),
            runnerRace: $runnerRace,
            externalCheckpointId: null, // Custom checkpoint
            name: $command->name,
            location: $command->location,
            distanceFromStart: $command->distanceFromStart,
            ascentFromStart: $command->ascentFromStart,
            descentFromStart: $command->descentFromStart,
            cutoff: $cutoff,
            assistanceAllowed: $command->assistanceAllowed,
        );

        $runnerRace->addCheckpoint($checkpoint);

        $this->runnerRacesCatalog->add($runnerRace);
    }
}
