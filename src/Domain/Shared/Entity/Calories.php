<?php

namespace App\Domain\Shared\Entity;

final readonly class Calories
{
    public function __construct(public int $value)
    {
        if ($this->value < 0) {
            throw new \DomainException(\sprintf('Calories cannot be negative: %d', $this->value));
        }
    }
}
