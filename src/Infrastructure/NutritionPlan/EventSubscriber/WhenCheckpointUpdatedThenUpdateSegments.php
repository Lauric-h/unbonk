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
        // Event should send previous CP + next CP
        // Get All Segments corresponding to all CP
        // Update metrics
        // Save
        $nutritionPlan = $this->nutritionPlansCatalog->getByRaceId($event->raceId);

        dd($event, $nutritionPlan);
    }
}