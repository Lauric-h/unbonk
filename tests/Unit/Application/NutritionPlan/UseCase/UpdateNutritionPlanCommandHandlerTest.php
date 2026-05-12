<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\UpdateNutritionPlan\UpdateNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\UpdateNutritionPlan\UpdateNutritionPlanCommandHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class UpdateNutritionPlanCommandHandlerTest extends TestCase
{
    public function testItRenamesNutritionPlan(): void
    {
        // Arrange
        $nutritionPlanId = 'nutrition-plan-id';
        $newName = 'My Updated Plan';

        $nutritionPlan = new NutritionPlanTestFixture()
            ->withId($nutritionPlanId)
            ->build();

        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $nutritionPlansCatalog->expects($this->once())
            ->method('get')
            ->with($nutritionPlanId)
            ->willReturn($nutritionPlan);

        $handler = new UpdateNutritionPlanCommandHandler($nutritionPlansCatalog);

        $command = new UpdateNutritionPlanCommand(
            nutritionPlanId: $nutritionPlanId,
            name: $newName,
        );

        // Act
        $handler($command);

        // Assert
        $this->assertSame($newName, $nutritionPlan->name);
    }

    public function testItCanSetNameToNull(): void
    {
        // Arrange
        $nutritionPlanId = 'nutrition-plan-id';

        $nutritionPlan = new NutritionPlanTestFixture()
            ->withId($nutritionPlanId)
            ->withName('Original Name')
            ->build();

        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $nutritionPlansCatalog->expects($this->once())
            ->method('get')
            ->with($nutritionPlanId)
            ->willReturn($nutritionPlan);

        $handler = new UpdateNutritionPlanCommandHandler($nutritionPlansCatalog);

        $command = new UpdateNutritionPlanCommand(
            nutritionPlanId: $nutritionPlanId,
            name: null,
        );

        // Act
        $handler($command);

        // Assert
        $this->assertNull($nutritionPlan->name);
    }
}
