<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommandHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class DeleteNutritionPlanCommandHandlerTest extends TestCase
{
    public function testDeleteNutritionPlan(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new DeleteNutritionPlanCommandHandler($repository);

        $nutritionPlan = (new NutritionPlanTestFixture())->build();
        $id = $nutritionPlan->id;

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
