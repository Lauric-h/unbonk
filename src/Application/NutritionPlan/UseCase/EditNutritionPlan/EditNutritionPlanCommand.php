<?php

namespace App\Application\NutritionPlan\UseCase\EditNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;

final readonly class EditNutritionPlanCommand implements CommandInterface
{
    public function __construct(public string $id, public string $runnerId, public string $name)
    {
    }
}