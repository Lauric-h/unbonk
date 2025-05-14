<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionItem;

use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class DeleteNutritionItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(private SegmentsCatalog $segmentsCatalog)
    {
    }

    public function __invoke(DeleteNutritionItemCommand $command): void
    {
        $segment = $this->segmentsCatalog->getByNutritionPlanAndId($command->nutritionPlanId, $command->segmentId);
        if ($command->getUserId() !== $segment->nutritionPlan->runnerId) {
            throw new AccessDeniedException();
        }

        $segment->removeNutritionItem($command->nutritionItemId);

        $this->segmentsCatalog->add($segment);
    }
}
