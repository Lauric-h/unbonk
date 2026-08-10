<?php

namespace App\Application\NutritionPlan\UseCase\EditNutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class EditNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlanRepositoryInterface $nutritionPlanRepository,
        private RunnerRaceReaderInterface $raceReader
    ) {
    }

    public function __invoke(EditNutritionPlanCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlanRepository->get($command->id);

        if (!$this->raceReader->belongsToRunner($nutritionPlan->getRaceId(), $command->runnerId)) {
            throw new NutritionPlanAccessDeniedException($command->runnerId);
        }

        $nutritionPlan->setName($command->name);

        $this->nutritionPlanRepository->update($nutritionPlan);
    }
}