<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\UpdateCheckpoint;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\ValueObject\Cutoff;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(UpdateCheckpointCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $cutoff = null !== $command->cutoffTime
            ? new Cutoff($command->cutoffTime)
            : null;

        // Generate IDs for potential new segments (if distance changes)
        $checkpointCount = $nutritionPlan->race->getCheckpoints()->count();
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        $nutritionPlan->updateCheckpoint(
            $command->checkpointId,
            $command->name,
            $command->location,
            $command->distanceFromStart,
            $command->ascentFromStart,
            $command->descentFromStart,
            $cutoff,
            $command->assistanceAllowed,
            $segmentIds,
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
