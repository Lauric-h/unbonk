<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionItem;

use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteNutritionItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
    ) {
    }

    public function __invoke(DeleteNutritionItemCommand $command): void
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

        $segmentPlan->removeItem($command->nutritionItemId);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
