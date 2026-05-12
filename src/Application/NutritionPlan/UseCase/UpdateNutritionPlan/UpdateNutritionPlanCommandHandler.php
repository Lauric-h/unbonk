<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\UpdateNutritionPlan;

use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
    ) {
    }

    public function __invoke(UpdateNutritionPlanCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $nutritionPlan->rename($command->name);
    }
}
