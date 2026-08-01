<?php

namespace App\Application\NutritionPlan\UseCase\GetMyNutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetMyNutritionPlanQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private NutritionPlanRepositoryInterface $nutritionPlanRepository,
        private RunnerRaceReaderInterface $runnerRaceReader,
        private NutritionPlanReadModelAssembler $assembler
    ) {
    }

    public function __invoke(GetMyNutritionPlanQuery $query): NutritionPlanReadModel
    {
        $nutritionPlan = $this->nutritionPlanRepository->get($query->nutritionPlanId);
        $race = $this->runnerRaceReader->get($nutritionPlan->getRunnerRaceId());

        if ($query->runnerId !== $race->runnerId) {
            throw new NutritionPlanAccessDeniedException($query->nutritionPlanId, $query->runnerId);
        }

        return $this->assembler->assemble($nutritionPlan, $race);
    }
}