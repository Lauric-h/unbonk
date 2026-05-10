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

        // Generate segment IDs for the configuration after removal
        $checkpointCount = $nutritionPlan->getCheckpointCount() - 1;
        $segmentIds = $this->generateSegmentIds($checkpointCount);

        $nutritionPlan->removeCheckpoint($command->checkpointId, $segmentIds);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }

    /**
     * Generate segment IDs based on checkpoint count.
     * Formula: segmentCount = checkpointCount - 1
     *
     * @return string[]
     */
    private function generateSegmentIds(int $checkpointCount): array
    {
        $segmentCount = $checkpointCount - 1;
        if ($segmentCount < 0) {
            return [];
        }

        $segmentIds = [];
        for ($i = 0; $i < $segmentCount; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        return $segmentIds;
    }
}
