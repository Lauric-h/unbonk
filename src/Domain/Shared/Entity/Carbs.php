<?php

namespace App\Domain\Shared\Entity;

final readonly class Carbs
{
    public function __construct(public int $value)
    {
        if ($this->value < 0) {
            throw new \DomainException(\sprintf('Carbs cannot be negative: %d', $this->value));
        }
    }
}
