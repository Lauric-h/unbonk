<?php

namespace App\Application\NutritionPlan\Exception;

class NutritionPlanAlreadyExistsException extends \RuntimeException
{
    public function __construct(string $nutritionPlanId)
    {
        parent::__construct(\sprintf('NutritionPlan %s already exists', $nutritionPlanId));
    }
}