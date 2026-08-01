<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

final readonly class Carbs
{
    private function __construct(public ?int $grams = null)
    {
        if ($this->grams && $this->grams < 0) {
            throw new \DomainException('Carbs cannot be negative.');
        }
    }

    public static function fromGrams(int $grams): self
    {
        return new self($grams);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function add(self $other): self
    {
        return new self($this->grams + $other->grams);
    }

    public function multiply(int $factor): self
    {
        return new self($this->grams * $factor);
    }
}
