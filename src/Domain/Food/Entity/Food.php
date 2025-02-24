<?php

namespace App\Domain\Food\Entity;

use App\Domain\Shared\DomainEventInterface;
use App\Domain\Shared\WithDomainEventInterface;

class Food
{
    public function __construct(
        public string $id,
        public Brand $brand,
        public string $name,
        public int $carbs,
        public IngestionType $ingestionType,
        public ?int $calories,
    ) {
    }
}