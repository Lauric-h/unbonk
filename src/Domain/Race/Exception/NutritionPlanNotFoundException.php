<?php

namespace App\Domain\Race\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class NutritionPlanNotFoundException extends NotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct(\sprintf('Nutrition plan with id %s not found', $id));
    }
}
