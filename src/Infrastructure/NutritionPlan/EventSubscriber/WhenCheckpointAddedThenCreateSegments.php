<?php

namespace App\Infrastructure\NutritionPlan\EventSubscriber;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Application\NutritionPlan\UseCase\CreateSegments\CreateSegmentsCommand;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\CheckpointAddedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class WhenCheckpointAddedThenCreateSegments
{
    public function __construct(private CommandBus $commandBus, private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(CheckpointAddedIntegrationEvent $event): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->getByRaceId($event->raceId);

        $points = [];
        foreach ($event->checkpoints as $checkpoint) {
            $points[] = PointDTO::fromArray((array) $checkpoint);
        }

        $this->commandBus->dispatch(new CreateSegmentsCommand($nutritionPlan->id, $points));
    }
}
