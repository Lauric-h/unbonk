<?php

namespace App\Infrastructure\NutritionPlan\EventSubscriber;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Domain\Shared\IdGeneratorInterface;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\RaceCreatedIntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class WhenRaceCreatedThenCreateNutritionPlan
{
    public function __construct(private CommandBus $commandBus, private IdGeneratorInterface $idGenerator)
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
