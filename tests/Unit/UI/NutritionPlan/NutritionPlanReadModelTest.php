<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class NutritionPlanReadModelTest extends TestCase
{
    public function testFromNutritionPlan(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $actual = NutritionPlanReadModel::fromNutritionPlan($nutritionPlan);

        $this->assertSame($nutritionPlan->id, $actual->id);
        $this->assertSame($nutritionPlan->race->runnerId, $actual->runnerId);
        $this->assertSame($nutritionPlan->race->name, $actual->race->name);
        $this->assertCount(2, $actual->segments); // Default fixture has 3 checkpoints = 2 segments
        $this->assertCount(3, $actual->race->checkpoints);
    }
}
