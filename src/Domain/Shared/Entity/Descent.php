<?php

namespace App\Domain\Shared\Entity;

final readonly class Descent
{
    public function __construct(public int $value)
    {
        if ($this->value < 0) {
            throw new \DomainException(\sprintf('Descent cannot be negative: %d', $this->value));
        }
    }
}
