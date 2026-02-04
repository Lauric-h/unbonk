<?php

namespace App\Domain\NutritionPlan\Exception;

final class ForbiddenNutritionPlanAccessException extends \Exception
{
    public function __construct(string $nutritionPlanId, string $runnerId)
    {
        parent::__construct(\sprintf('Runner %s cannot access nutrition plan %s', $runnerId, $nutritionPlanId));
    }
}
