<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\AddCheckpoint;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\CheckpointType;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\ValueObject\Cutoff;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class AddCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(AddCheckpointCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $cutoff = null !== $command->cutoffTime
            ? new Cutoff($command->cutoffTime)
            : null;

        $checkpoint = new Checkpoint(
            $this->idGenerator->generate(),
            null,
            $command->name,
            $command->location,
            $command->distanceFromStart,
            $command->ascentFromStart,
            $command->descentFromStart,
            $cutoff,
            $command->assistanceAllowed,
            $nutritionPlan->race,
            CheckpointType::Intermediate,
        );

        $checkpointCount = $nutritionPlan->race->getCheckpoints()->count() + 1;
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        $nutritionPlan->addCustomCheckpoint($checkpoint, $segmentIds);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
