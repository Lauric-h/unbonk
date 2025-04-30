<?php

namespace App\Infrastructure\NutritionPlan\EventSubscriber;

use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Infrastructure\Shared\Event\CheckpointUpdatedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class WhenCheckpointUpdatedThenUpdateSegments
{
    public function __construct(public NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(CheckpointUpdatedIntegrationEvent $event): void
    {
        // TODO
        // Trigger event only if metrics were changed
        // Integration Event should contain all CP sorted
        // Update all segments with CreateSegmentsCommandHandler
        // Save
        $nutritionPlan = $this->nutritionPlansCatalog->getByRaceId($event->raceId);

        dd($event, $nutritionPlan);
    }
}