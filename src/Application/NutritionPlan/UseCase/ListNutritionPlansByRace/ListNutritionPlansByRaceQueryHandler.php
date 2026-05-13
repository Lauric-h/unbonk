<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\ListNutritionPlansByRace;

use App\Application\NutritionPlan\ReadModel\NutritionPlanListItemReadModel;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListNutritionPlansByRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
    ) {
    }

    /**
     * @return array<NutritionPlanListItemReadModel>
     */
    public function __invoke(ListNutritionPlansByRaceQuery $query): array
    {
        $nutritionPlans = $this->nutritionPlansCatalog->findByRaceId($query->raceId);

        return array_map(
            static fn (NutritionPlan $nutritionPlan): NutritionPlanListItemReadModel => NutritionPlanListItemReadModel::fromNutritionPlan($nutritionPlan),
            $nutritionPlans
        );
    }
}
