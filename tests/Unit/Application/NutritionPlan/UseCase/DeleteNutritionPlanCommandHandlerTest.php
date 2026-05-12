<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\DeleteNutritionPlan\DeleteNutritionPlanCommandHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class DeleteNutritionPlanCommandHandlerTest extends TestCase
{
    public function testItDeletesNutritionPlan(): void
    {
        // Arrange
        $nutritionPlanId = 'nutrition-plan-id';

        $nutritionPlan = new NutritionPlanTestFixture()
            ->withId($nutritionPlanId)
            ->build();

        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $nutritionPlansCatalog->expects($this->once())
            ->method('get')
            ->with($nutritionPlanId)
            ->willReturn($nutritionPlan);

        $nutritionPlansCatalog->expects($this->once())
            ->method('remove')
            ->with($nutritionPlan);

        $handler = new DeleteNutritionPlanCommandHandler($nutritionPlansCatalog);

        $command = new DeleteNutritionPlanCommand(
            nutritionPlanId: $nutritionPlanId,
        );

        // Act
        $handler($command);

        // Assert: expectations verified by PHPUnit
    }
}
