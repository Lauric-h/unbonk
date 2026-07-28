<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlanRepositoryInterface $nutritionPlansCatalog,
    ) {
    }

    public function __invoke(DeleteNutritionPlanCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $this->nutritionPlansCatalog->remove($nutritionPlan);
    }
}
