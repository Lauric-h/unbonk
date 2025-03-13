<?php

namespace App\Domain\Food\Exception;

final class FoodCaloriesMustBePositiveException extends \DomainException
{
    public function __construct(int $calories)
    {
        parent::__construct(\sprintf('Food Calories must be a positive integer, got %d', $calories));
    }
}
