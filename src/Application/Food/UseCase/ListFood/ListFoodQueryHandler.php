<?php

namespace App\Application\Food\UseCase\ListFood;

use App\Domain\Food\Repository\FoodsCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;
use App\UI\Http\Rest\Food\View\ListFoodReadModel;

final readonly class ListFoodQueryHandler implements QueryHandlerInterface
{
    public function __construct(private FoodsCatalog $foodsCatalog)
    {
    }

    public function __invoke(ListFoodQuery $query): ListFoodReadModel
    {
        $foods = $this->foodsCatalog->getAll(
            $query->brandId,
            $query->name,
            $query->ingestionType
        );

        return ListFoodReadModel::fromFoods($foods);
    }
}
