<?php

namespace App\Application\NutritionPlan\UseCase\ListNutritionPlans;

use App\Application\NutritionPlan\ReadModel\NutritionPlanListItemReadModel;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListNutritionPlansQueryHandler implements QueryHandlerInterface
{
    public function __construct(private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    /**
     * @return array<NutritionPlanListItemReadModel>
     */
    public function __invoke(ListNutritionPlansQuery $query): array
    {
        $nutritionPlans = $this->nutritionPlansCatalog->getByRunner($query->runnerId);

        return array_map(
            static fn (NutritionPlan $nutritionPlan): NutritionPlanListItemReadModel => NutritionPlanListItemReadModel::fromNutritionPlan($nutritionPlan),
            $nutritionPlans
        );
    }
}
