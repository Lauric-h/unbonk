<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlanRepositoryInterface $nutritionPlanRepository,
        private RunnerRaceReaderInterface $runnerRaceReader,
    ) {
    }

    public function __invoke(DeleteNutritionPlanCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlanRepository->get($command->nutritionPlanId);
        if (!$this->runnerRaceReader->belongsToRunner($nutritionPlan->getRunnerRaceId(), $command->runnerId)) {
            throw new NutritionPlanAccessDeniedException($command->runnerId);
        }

        $this->nutritionPlanRepository->deleteByRunnerRaceId($nutritionPlan->getRunnerRaceId());
    }
}