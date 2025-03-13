<?php

namespace App\Domain\Food\Exception;

final class FoodCarbsMustBePositiveException extends \DomainException
{
    public function __construct(int $carbs)
    {
        parent::__construct(\sprintf('Food Carbs must be a positive integer, got %d', $carbs));
    }
}
