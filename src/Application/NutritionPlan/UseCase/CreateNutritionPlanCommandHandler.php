<?php

namespace App\Application\NutritionPlan\UseCase;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class CreateNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(CreateNutritionPlanCommand $command): void
    {
        $nutritionPlan = new NutritionPlan(
            $command->id,
            $command->raceId,
            $command->runnerId,
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
