<?php

namespace App\Domain\Shared\Entity;

final readonly class Carbs
{
    public const DEFAULT_PER_HOUR = 60;

    public readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \DomainException(\sprintf('Carbs cannot be negative: %d', $value));
        }

        $this->value = 0 === $value ? self::DEFAULT_PER_HOUR : $value;
    }
}
