<?php

namespace App\Application\NutritionPlan\Exception;

final class NutritionPlanAccessDeniedException extends \RuntimeException
{
    public function __construct(string $runnerId)
    {
        parent::__construct(\sprintf('NutritionPlan does not belong to Runner %s', $runnerId));
    }
}