<?php

namespace App\Application\Food\UseCase\GetFood;

use App\Application\Food\ReadModel\FoodReadModel;
use App\Domain\Food\Repository\FoodsCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetFoodQueryHandler implements QueryHandlerInterface
{
    public function __construct(private FoodsCatalog $foodsCatalog)
    {
    }

    public function __invoke(GetFoodQuery $query): FoodReadModel
    {
        $food = $this->foodsCatalog->get($query->id);

        return FoodReadModel::fromFood($food);
    }
}
