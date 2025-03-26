<?php

namespace App\Domain\Shared\Entity;

final readonly class Distance
{
    public function __construct(public int $value)
    {
        if ($this->value < 0) {
            throw new \DomainException(\sprintf('Distance cannot be negative: %d', $this->value));
        }
    }
}
