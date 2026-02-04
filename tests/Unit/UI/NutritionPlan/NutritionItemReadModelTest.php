<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionItemReadModel;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use PHPUnit\Framework\TestCase;

final class NutritionItemReadModelTest extends TestCase
{
    public function testFromNutritionItem(): void
    {
        $nutritionItem = new NutritionItem(
            'id',
            'externalRef',
            'name',
            new Carbs(100),
            new Quantity(2),
            new Calories(300)
        );

        $expected = new NutritionItemReadModel(
            'id',
            'externalRef',
            'name',
            100,
            2,
            300
        );

        $actual = NutritionItemReadModel::fromNutritionItem($nutritionItem);

        $this->assertEquals($expected, $actual);
    }
}
