<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\NutritionPlanListItemReadModel;
use App\Application\NutritionPlan\UseCase\ListNutritionPlans\ListNutritionPlansQuery;
use App\Application\NutritionPlan\UseCase\ListNutritionPlans\ListNutritionPlansQueryHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class ListNutritionPlansQueryHandlerTest extends TestCase
{
    public function testListNutritionPlansReturnsReadModels(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $handler = new ListNutritionPlansQueryHandler($nutritionPlansCatalog);

        $runnerId = 'runner-123';

        $nutritionPlan1 = new NutritionPlanTestFixture()
            ->withId('plan-1')
            ->withName('UTMB Nutrition Plan')
            ->build();

        $nutritionPlan2 = new NutritionPlanTestFixture()
            ->withId('plan-2')
            ->withName('TDG Nutrition Plan')
            ->build();

        $nutritionPlans = [$nutritionPlan1, $nutritionPlan2];

        $nutritionPlansCatalog->expects($this->once())
            ->method('getByRunner')
            ->with($runnerId)
            ->willReturn($nutritionPlans);

        $result = ($handler)(new ListNutritionPlansQuery($runnerId));

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(NutritionPlanListItemReadModel::class, $result);

        // Check first nutrition plan
        $this->assertSame('plan-1', $result[0]->id);
        $this->assertSame('UTMB Nutrition Plan', $result[0]->nutritionPlanName);
        $this->assertSame('Test Event', $result[0]->eventName);
        $this->assertSame('Test Event', $result[0]->raceName);
        $this->assertSame(50000, $result[0]->raceDistance);
        $this->assertInstanceOf(\DateTimeImmutable::class, $result[0]->raceDate);
        $this->assertSame(0, $result[0]->totalCarbs); // No nutrition items added

        // Check second nutrition plan
        $this->assertSame('plan-2', $result[1]->id);
        $this->assertSame('TDG Nutrition Plan', $result[1]->nutritionPlanName);
    }

    public function testListNutritionPlansReturnsEmptyArrayWhenNoPlans(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $handler = new ListNutritionPlansQueryHandler($nutritionPlansCatalog);

        $runnerId = 'runner-123';

        $nutritionPlansCatalog->expects($this->once())
            ->method('getByRunner')
            ->with($runnerId)
            ->willReturn([]);

        $result = ($handler)(new ListNutritionPlansQuery($runnerId));

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
