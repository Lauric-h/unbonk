<?php

namespace App\Application\NutritionPlan\UseCase\GetNutritionPlan;

use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetNutritionPlanQueryHandler implements QueryHandlerInterface
{
    public function __construct(private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(GetNutritionPlanQuery $query): NutritionPlanReadModel
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($query->id);

        return NutritionPlanReadModel::fromNutritionPlan($nutritionPlan);
    }
}