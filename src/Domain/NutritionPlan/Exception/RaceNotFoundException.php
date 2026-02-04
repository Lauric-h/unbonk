<?php

namespace App\Domain\NutritionPlan\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class RaceNotFoundException extends NotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct(\sprintf('Race with id %s not found', $id));
    }
}
