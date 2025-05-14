<?php


namespace App\Application\NutritionPlan\UseCase\GetNutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetNutritionPlanQueryHandler implements QueryHandlerInterface
{
    public function __construct(private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(GetNutritionPlanQuery $query): NutritionPlanReadModel
    {
        $nutritionPlan = $this->nutritionPlansCatalog->getForUser($query->id, $query->getUserId());

        return NutritionPlanReadModel::fromNutritionPlan($nutritionPlan);
    }
}
