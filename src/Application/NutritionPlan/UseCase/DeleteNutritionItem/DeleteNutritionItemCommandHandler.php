<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionItem;

use App\Domain\Race\Repository\SegmentsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteNutritionItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(private SegmentsCatalog $segmentsCatalog)
    {
    }

    public function __invoke(DeleteNutritionItemCommand $command): void
    {
        $segment = $this->segmentsCatalog->getByNutritionPlanAndId($command->nutritionPlanId, $command->segmentId);

        $segment->removeNutritionItem($command->nutritionItemId);

        $this->segmentsCatalog->add($segment);
    }
}
