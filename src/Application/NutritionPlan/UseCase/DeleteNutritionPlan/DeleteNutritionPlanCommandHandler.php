<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(DeleteNutritionPlanCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->getForUser($command->id, $command->getUserId());
        $this->nutritionPlansCatalog->remove($nutritionPlan);
    }
}
