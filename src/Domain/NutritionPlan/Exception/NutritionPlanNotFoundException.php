<?php

namespace App\Domain\NutritionPlan\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class NutritionPlanNotFoundException extends NotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct(\sprintf('Nutrition plan with id %s not found', $id));
    }
}
