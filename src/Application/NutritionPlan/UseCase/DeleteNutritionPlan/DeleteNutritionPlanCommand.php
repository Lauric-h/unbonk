<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;

final readonly class DeleteNutritionPlanCommand implements CommandInterface
{
    public function __construct(public string $id)
    {
    }
}
