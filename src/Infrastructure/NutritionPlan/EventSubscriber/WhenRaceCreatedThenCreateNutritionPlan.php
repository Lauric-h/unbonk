<?php

namespace App\Infrastructure\NutritionPlan\EventSubscriber;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlanCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\RaceCreatedIntegrationEvent;
use App\SharedKernel\IdGenerator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class WhenRaceCreatedThenCreateNutritionPlan
{
    public function __construct(private CommandBus $commandBus, private IdGenerator $idGenerator)
    {
    }

    public function __invoke(RaceCreatedIntegrationEvent $event): void
    {
        $this->commandBus->dispatch(new CreateNutritionPlanCommand(
            $this->idGenerator->generate(),
            $event->id,
            $event->runnerId
        ));
    }
}
