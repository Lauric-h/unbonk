<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\ListNutritionPlansByRace\ListNutritionPlansByRaceQuery;
use App\Application\NutritionPlan\UseCase\ListNutritionPlansByRace\ListNutritionPlansByRaceQueryHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class ListNutritionPlansByRaceQueryHandlerTest extends TestCase
{
    public function testItReturnsNutritionPlansForGivenRace(): void
    {
        // Arrange
        $raceId = 'race-id';

        $nutritionPlan1 = new NutritionPlanTestFixture()
            ->withId('plan-1')
            ->withName('Plan 1')
            ->build();

        $nutritionPlan2 = new NutritionPlanTestFixture()
            ->withId('plan-2')
            ->withName('Plan 2')
            ->build();

        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $nutritionPlansCatalog->expects($this->once())
            ->method('findByRaceId')
            ->with($raceId)
            ->willReturn([$nutritionPlan1, $nutritionPlan2]);

        $handler = new ListNutritionPlansByRaceQueryHandler($nutritionPlansCatalog);

        $query = new ListNutritionPlansByRaceQuery(raceId: $raceId);

        // Act
        $result = $handler($query);

        // Assert
        $this->assertCount(2, $result);
        $this->assertSame('Plan 1', $result[0]->nutritionPlanName);
        $this->assertSame('Plan 2', $result[1]->nutritionPlanName);
    }

    public function testItReturnsEmptyArrayWhenNoPlansFound(): void
    {
        // Arrange
        $raceId = 'race-id';

        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $nutritionPlansCatalog->expects($this->once())
            ->method('findByRaceId')
            ->with($raceId)
            ->willReturn([]);

        $handler = new ListNutritionPlansByRaceQueryHandler($nutritionPlansCatalog);

        $query = new ListNutritionPlansByRaceQuery(raceId: $raceId);

        // Act
        $result = $handler($query);

        // Assert
        $this->assertCount(0, $result);
    }
}
