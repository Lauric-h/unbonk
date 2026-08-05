<?php

namespace App\Application\NutritionPlan\UseCase\ListMyNutritionPlans;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class ListMyNutritionPlansQuery implements QueryInterface
{
    public function __construct(public string $runnerId)
    {
    }
}