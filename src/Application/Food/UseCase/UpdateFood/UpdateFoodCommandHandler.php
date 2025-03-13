<?php

namespace App\Application\Food\UseCase\UpdateFood;

use App\Application\Food\Exception\InvalidIngestionTypeValueException;
use App\Domain\Food\Entity\IngestionType;
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

        $ingestionType = IngestionType::tryFrom($command->ingestionType);
        if (null === $ingestionType) {
            throw new InvalidIngestionTypeValueException($command->ingestionType);
        }

        $food->update(
            name: $command->name,
            carbs: $command->carbs,
            ingestionType: $ingestionType,
            calories: $command->calories
        );

        $this->foodsCatalog->add($food);
    }
}
