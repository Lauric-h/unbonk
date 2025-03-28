<?php

namespace App\Infrastructure\NutritionPlan\EventSubscriber;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\RaceDeletedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class WhenRaceRemovedThenRemoveNutritionPlan
{
    public function __construct(private CommandBus $commandBus, private NutritionPlansCatalog $nutritionPlansCatalog)
    {
    }

    public function __invoke(RaceDeletedIntegrationEvent $event): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->getByRaceId($event->raceId);
        $this->commandBus->dispatch(new DeleteNutritionPlanCommand(
            $nutritionPlan->id,
        ));
    }
}
