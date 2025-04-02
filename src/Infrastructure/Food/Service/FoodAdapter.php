<?php

namespace App\Infrastructure\Food\Service;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Domain\Food\DTO\FoodDTO;
use App\Domain\Food\Port\FoodPort;
use App\Infrastructure\Shared\Bus\QueryBus;

readonly class FoodAdapter implements FoodPort
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function getById(string $id): FoodDTO
    {
        $food = $this->queryBus->query(new GetFoodQuery($id));

        return new FoodDTO(
            id: $food->id,
            name: $food->name,
            carbs: $food->carbs,
            calories: $food->calories,
        );
    }
}
