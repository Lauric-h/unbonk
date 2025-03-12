<?php

namespace App\Domain\Food\Entity;

use App\Domain\Food\Exception\FoodCaloriesMustBePositiveException;
use App\Domain\Food\Exception\FoodCarbsMustBePositiveException;

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

    public function update(
        string $name,
        int $carbs,
        IngestionType $ingestionType,
        ?int $calories,
    ): void {
        if ($carbs <= 0) {
            throw new FoodCarbsMustBePositiveException($carbs);
        }

        if (null !== $calories && $calories <= 0) {
            throw new FoodCaloriesMustBePositiveException($calories);
        }

        $this->name = $name;
        $this->carbs = $carbs;
        $this->ingestionType = $ingestionType;
        $this->calories = $calories;
    }
}
