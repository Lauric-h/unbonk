<?php

namespace App\Domain\NutritionPlan\Entity;

final readonly class Quantity
{
    public function __construct(public int $value = 1)
    {
        if ($this->value < 0) {
            throw new \DomainException(\sprintf('Quantity cannot be negative: %d', $this->value));
        }
    }
}
