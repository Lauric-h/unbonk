<?php

namespace App\Tests\Unit\Infrastructure\NutritionPlan\Listener;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Application\NutritionPlan\UseCase\CreateSegments\CreateSegmentsCommand;
use App\Domain\Race\Entity\NutritionPlan;
use App\Infrastructure\NutritionPlan\EventSubscriber\WhenRaceCheckpointsChangedThenReplaceSegments;
use App\Infrastructure\Race\DTO\CheckpointDTO;
use App\Infrastructure\Race\Persistence\Repository\DoctrineNutritionPlansCatalog;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Event\RaceCheckpointsChangedIntegrationEvent;
use PHPUnit\Framework\TestCase;

final class WhenCheckpointAddedThenCreateSegmentsTest extends TestCase
{
    public function testItDispatchesCommandHandler(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $repository = $this->createMock(DoctrineNutritionPlansCatalog::class);
        $listener = new WhenRaceCheckpointsChangedThenReplaceSegments($commandBus, $repository);

        $raceId = 'raceId';
        $event = new RaceCheckpointsChangedIntegrationEvent(
            'raceId',
            [
                new CheckpointDTO('externalIdStart', 'Start', 'location', 0, 0, 0, 0),
                new CheckpointDTO('externalIdCP1', 'Aid', 'location', 12, 120, 1000, 1000),
                new CheckpointDTO('externalIdFinish', 'Finish', 'location', 42, 300, 2000, 2000),
            ]
        );

        $nutritionPlan = new NutritionPlan('id', $raceId, 'runnerId');

        $repository->expects($this->once())
            ->method('getByRaceId')
            ->with($raceId)
            ->willReturn($nutritionPlan);

        $points = [
            new PointDTO('externalIdStart', 0, 0, 0, 0),
            new PointDTO('externalIdCP1', 12, 120, 1000, 1000),
            new PointDTO('externalIdFinish', 42, 300, 2000, 2000),
        ];

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with(new CreateSegmentsCommand($nutritionPlan->id, $points));

        ($listener)($event);
    }
}
