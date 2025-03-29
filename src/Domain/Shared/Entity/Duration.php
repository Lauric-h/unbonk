<?php

namespace App\Domain\Shared\Entity;

final readonly class Duration
{
    public function __construct(public int $minutes)
    {
        if ($this->minutes < 0) {
            throw new \DomainException(\sprintf('Minutes cannot be negative: %d', $this->minutes));
        }
    }

    public function hours(): float
    {
        return $this->minutes / 3600;
    }
}
