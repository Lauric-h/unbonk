<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommandHandler;
use App\Domain\Race\Entity\NutritionPlan;
use App\Infrastructure\Race\Persistence\Repository\DoctrineNutritionPlansCatalog;
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
