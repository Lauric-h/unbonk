<?php

namespace App\Application\Food\UseCase\CreateFood;

use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Domain\Food\Repository\BrandsCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class CreateFoodCommandHandler implements CommandHandlerInterface
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(CreateFoodCommand $command): void
    {
        $brand = $this->brandsCatalog->get($command->brandId);

        $brand->addFood(new Food(
            id: $command->id,
            brand: $brand,
            name: $command->name,
            carbs: $command->carbs,
            ingestionType: IngestionType::from($command->ingestionType),
            calories: $command->calories,
        ));

        $this->brandsCatalog->add($brand);
    }
}
