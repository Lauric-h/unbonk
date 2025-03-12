<?php

namespace App\Application\Food\UseCase\DeleteFood;

use App\Domain\Food\Repository\FoodsCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteFoodCommandHandler implements CommandHandlerInterface
{
    public function __construct(private FoodsCatalog $foodsCatalog)
    {
    }

    public function __invoke(DeleteFoodCommand $command): void
    {
        $this->foodsCatalog->remove($command->id);
    }
}
