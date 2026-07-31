<?php

namespace App\Application\NutritionPlan\Exception;

final class NutritionPlanAccessDeniedException extends \RuntimeException
{
    public function __construct(string $nutritionPlanId, string $runnerId)
    {
        parent::__construct(\sprintf('NutritionPlan %s does not belong to Runner %s', $nutritionPlanId, $runnerId));
    }
}