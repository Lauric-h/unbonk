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

        // Generate segment IDs (may be needed if distance changes)
        $checkpointCount = $nutritionPlan->getCheckpointCount();
        $segmentIds = $this->generateSegmentIds($checkpointCount);

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

    /**
     * Generate segment IDs based on checkpoint count.
     * Formula: segmentCount = checkpointCount - 1.
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
