<?php

namespace App\Application\NutritionPlan\UseCase\AddNutritionItem;

use App\Domain\Shared\Bus\CommandHandlerInterface;

final class AddNutritionItemCommandHandler implements CommandHandlerInterface
{
    public function __construct()
    {
    }

    public function __invoke(AddNutritionItemCommand $command): void
    {
        // TODO: Implement __invoke() method.
        // get food info (dto)
        // Create NutritionItem
        // Add it to Segment
        // Save
    }
}