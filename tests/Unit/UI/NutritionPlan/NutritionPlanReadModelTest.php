<?php

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Domain\Race\Entity\NutritionPlan;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class NutritionPlanReadModelTest extends TestCase
{
    public function testFromNutritionPlan(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $expected = new NutritionPlanReadModel(
            id: 'id',
            raceId: 'raceId',
            runnerId: 'runnerId',
            segments: []
        );

        $actual = NutritionPlanReadModel::fromNutritionPlan($nutritionPlan);

        $this->assertEquals($expected, $actual);
    }
}
