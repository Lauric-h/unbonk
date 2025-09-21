<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommandHandler;
use App\Domain\Race\Entity\NutritionPlan;
use App\Infrastructure\Race\Persistence\Repository\DoctrineNutritionPlansCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteNutritionPlanCommandHandlerTest extends TestCase
{
    public function testDeleteNutritionPlan(): void
    {
        $repository = $this->createMock(DoctrineNutritionPlansCatalog::class);
        $handler = new DeleteNutritionPlanCommandHandler($repository);
        $id = 'npId';

        $nutritionPlan = new NutritionPlan(
            $id,
            'raceId',
            'runnerId',
        );

        $repository->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($nutritionPlan);

        $repository->expects($this->once())
            ->method('remove')
            ->with($nutritionPlan);

        ($handler)(new DeleteNutritionPlanCommand($id));
    }
}
