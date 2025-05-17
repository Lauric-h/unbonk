<?php

namespace App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity;

use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateNutritionItemQuantityCommandHandler implements CommandHandlerInterface
{
    public function __construct(public SegmentsCatalog $segmentsCatalog)
    {
    }

    public function __invoke(UpdateNutritionItemQuantityCommand $command): void
    {
        $segment = $this->segmentsCatalog->get($command->segmentId);
        $nutritionItem = $segment->getNutritionItemById($command->nutritionItemId);

        if (null === $nutritionItem) {
            throw new \InvalidArgumentException(sprintf('Nutrition item with id "%s" not found', $command->nutritionItemId));
        }

        if (0 === $command->quantity) {
            $segment->removeNutritionItem($nutritionItem->id);
        } else {
            $nutritionItem->quantity = new Quantity($command->quantity);
        }

        $this->segmentsCatalog->add($segment);
    }
}
