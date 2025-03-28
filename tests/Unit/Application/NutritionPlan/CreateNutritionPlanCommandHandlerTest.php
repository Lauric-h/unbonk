<?php

namespace App\Tests\Unit\Application\NutritionPlan;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlanCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\NutritionPlan\Persistence\DoctrineNutritionPlansCatalog;
use PHPUnit\Framework\TestCase;

final class CreateNutritionPlanCommandHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $id = 'id';
        $raceId = 'raceId';
        $runnerId = 'runnerId';

        $command = new CreateNutritionPlanCommand($id, $raceId, $runnerId);
        $repository = $this->createMock(DoctrineNutritionPlansCatalog::class);
        $handler = new CreateNutritionPlanCommandHandler($repository);

        $nutritionPlan = new NutritionPlan(
            $id,
            $raceId,
            $runnerId
        );

        $repository->expects($this->once())
            ->method('add')
            ->with($nutritionPlan);

        ($handler)($command);
    }
}
