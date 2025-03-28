<?php

namespace App\Application\Food\UseCase\DeleteFood;

use App\Domain\Food\Repository\FoodsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteFoodCommandHandler implements CommandHandlerInterface
{
    public function __construct(private FoodsCatalog $foodsCatalog)
    {
    }

    public function __invoke(DeleteFoodCommand $command): void
    {
        $food = $this->foodsCatalog->get($command->id);
        $this->foodsCatalog->remove($food);
    }
}
