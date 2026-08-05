<?php

namespace App\Application\NutritionPlan\UseCase\ListMyNutritionPlans;

use App\Application\NutritionPlan\UseCase\ListMyNutritionPlans\ReadModel\NutritionPlanSummaryReadModel;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListMyNutritionPlansQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private NutritionPlanRepositoryInterface $nutritionPlanRepository,
        private RunnerRaceReaderInterface $raceReader
    ) {
    }

    /**
     * @return NutritionPlanSummaryReadModel[]
     */
    public function __invoke(ListMyNutritionPlansQuery $query): array
    {
        $runnerRaces = $this->raceReader->listForRunner($query->runnerId);

        $readModels = [];

        foreach ($runnerRaces as $runnerRace) {
            $plan = $this->nutritionPlanRepository->findByRunnerRaceId($runnerRace->id);

            if (null === $plan) {
                continue;
            }

            $readModels[] = new NutritionPlanSummaryReadModel(
                id: $plan->getId(),
                name: $plan->getName(),
                raceName: $runnerRace->name,
                raceStartDateTime: $runnerRace->startDateTime,
                totalCarbsInGrams: $plan->totalCarbs()?->grams,
            );
        }

        return $readModels;
    }
}