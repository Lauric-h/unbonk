<?php

namespace App\Tests\Unit\Infrastructure\NutritionPlan\Listener;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Infrastructure\NutritionPlan\EventSubscriber\WhenRaceCreatedThenCreateNutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\RaceCreatedIntegrationEvent;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class WhenRaceCreatedThenCreateNutritionPlanTest extends TestCase
{
    public function testDispatchCreateNutritionPlan(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $idGenerator = new MockIdGenerator('new-id');
        $raceId = 'raceId';
        $runnerId = 'runnerId';
        $raceCreatedIntegrationEvent = new RaceCreatedIntegrationEvent($raceId, $runnerId);

        $expectedCommand = new CreateNutritionPlanCommand(
            $idGenerator->generate(),
            $raceId,
            $runnerId,
        );

        $subscriber = new WhenRaceCreatedThenCreateNutritionPlan($commandBus, $idGenerator);

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($expectedCommand);

        ($subscriber)($raceCreatedIntegrationEvent);
    }
}
