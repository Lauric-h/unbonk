<?php

namespace App\Domain\Shared\Entity;

final readonly class Ascent
{
    public function __construct(public int $value)
    {
        if ($this->value < 0) {
            throw new \DomainException(\sprintf('Ascent cannot be negative: %d', $this->value));
        }
    }
}
