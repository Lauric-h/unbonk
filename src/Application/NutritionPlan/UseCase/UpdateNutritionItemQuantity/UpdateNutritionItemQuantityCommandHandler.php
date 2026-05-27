<?php

namespace App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity;

use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateNutritionItemQuantityCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
    ) {
    }

    public function __invoke(UpdateNutritionItemQuantityCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);
        
        $segmentPlan = $nutritionPlan->getSegmentPlanBySegmentId($command->segmentId);
        
        if (null === $segmentPlan) {
            throw new \DomainException(
                sprintf('Segment plan for segment %s not found in nutrition plan %s', 
                    $command->segmentId, 
                    $command->nutritionPlanId
                )
            );
        }
        
        $nutritionItem = $segmentPlan->getItemById($command->nutritionItemId);

        if (null === $nutritionItem) {
            throw new \DomainException(
                sprintf('Nutrition item with id "%s" not found', $command->nutritionItemId)
            );
        }

        if (0 === $command->quantity) {
            $segmentPlan->removeItem($nutritionItem->id);
        } else {
            $nutritionItem->quantity = new Quantity($command->quantity);
        }

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
