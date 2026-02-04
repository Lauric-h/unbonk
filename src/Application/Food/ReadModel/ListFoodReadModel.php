<?php

namespace App\Application\Food\ReadModel;

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
            )
        );
    }
}
