<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionItemReadModel;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class NutritionItemReadModelTest extends TestCase
{
    public function testFromNutritionItem(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $nutritionPlan->getSegmentPlans()->first();

        $this->assertInstanceOf(SegmentNutritionPlan::class, $segmentPlan);

        $nutritionItem = new NutritionItem(
            'id',
            $segmentPlan,
            'externalRef',
            new Quantity(2),
            new Carbs(100)
        );

        $expected = new NutritionItemReadModel(
            'id',
            'externalRef',
            2,
            100
        );

        $actual = NutritionItemReadModel::fromNutritionItem($nutritionItem);

        $this->assertEquals($expected, $actual);
    }
}
