<?php

namespace App\Tests\Unit\Infrastructure\NutritionPlan\Listener;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Infrastructure\NutritionPlan\EventSubscriber\WhenRaceRemovedThenRemoveNutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\RaceDeletedIntegrationEvent;
use PHPUnit\Framework\TestCase;

final class WhenRaceRemovedThenRemoveNutritionPlanTest extends TestCase
{
    public function testDispatchDeleteCommandHandler(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $event = new RaceDeletedIntegrationEvent('raceId');
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
        );

        $repository->expects($this->once())
            ->method('getByRaceId')
            ->with($event->raceId)
            ->willReturn($nutritionPlan);

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with(new DeleteNutritionPlanCommand($nutritionPlan->id));

        $eventDispatcher = new WhenRaceRemovedThenRemoveNutritionPlan($commandBus, $repository);

        ($eventDispatcher)($event);
    }
}
