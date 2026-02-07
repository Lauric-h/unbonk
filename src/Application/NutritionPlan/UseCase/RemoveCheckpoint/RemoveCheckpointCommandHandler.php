<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\RemoveCheckpoint;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class RemoveCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(RemoveCheckpointCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        // After removing a checkpoint, we'll have one less segment
        $checkpointCount = $nutritionPlan->race->getCheckpoints()->count() - 1;
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        $nutritionPlan->removeCheckpoint($command->checkpointId, $segmentIds);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
