<?php

namespace App\Application\NutritionPlan\UseCase\GetNutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepository;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetNutritionPlanQueryHandler implements QueryHandlerInterface
{
    public function __construct(private NutritionPlanRepository $nutritionPlansCatalog)
    {
    }

    public function __invoke(GetNutritionPlanQuery $query): NutritionPlanReadModel
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($query->id);

        return NutritionPlanReadModel::fromNutritionPlan($nutritionPlan);
    }
}
