<?php

namespace App\Application\Food\UseCase\UpdateFood;

use App\Domain\Food\Repository\FoodsCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateFoodCommandHandler implements CommandHandlerInterface
{
    public function __construct(private FoodsCatalog $foodsCatalog)
    {
    }

    public function __invoke(UpdateFoodCommand $command): void
    {
        $food = $this->foodsCatalog->get($command->id);

        $food->update(
            name: $command->name,
            carbs: $command->carbs,
            ingestionType: $command->ingestionType,
            calories: $command->calories
        );

        $this->foodsCatalog->add($food);
    }
}
