<?php

namespace App\UI\Http\Rest\Food\View;

use App\Domain\Food\Entity\Food;

final readonly class ListFoodReadModel
{
    /**
     * @param FoodReadModel[] $foods
     */
    public function __construct(public array $foods)
    {
    }

    /**
     * @param Food[] $foods
     */
    public static function fromFoods(array $foods): self
    {
        return new self(
            foods: array_map(
                static fn ($food) => FoodReadModel::fromFood($food),
                $foods
            ));
    }
}
