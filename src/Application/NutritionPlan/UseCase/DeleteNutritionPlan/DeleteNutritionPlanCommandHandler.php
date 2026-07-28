<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Domain\NutritionPlan\Repository\NutritionPlanRepository;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlanRepository $nutritionPlansCatalog,
    ) {
    }

    public function __invoke(DeleteNutritionPlanCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $this->nutritionPlansCatalog->remove($nutritionPlan);
    }
}
