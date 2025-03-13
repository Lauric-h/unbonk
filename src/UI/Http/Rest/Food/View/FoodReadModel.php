<?php

namespace App\UI\Http\Rest\Food\View;

use App\Domain\Food\Entity\Food;

final readonly class FoodReadModel
{
    public function __construct(
        public string $id,
        public string $brandName,
        public string $name,
        public int $carbs,
        public string $ingestionType,
        public ?int $calories,
    ) {
    }

    public static function fromFood(Food $food): self
    {
        return new self(
            id: $food->id,
            brandName: $food->brand->name,
            name: $food->name,
            carbs: $food->carbs,
            ingestionType: $food->ingestionType->value,
            calories: $food->calories,
        );
    }
}
