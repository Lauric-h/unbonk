<?php

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\CheckpointReadModel;
use App\Application\NutritionPlan\ReadModel\NutritionItemReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentReadModel;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class SegmentReadModelTest extends TestCase
{
    public function testFromSegment(): void
    {
        $nutritionPlan = (new NutritionPlanTestFixture())->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(\App\Domain\NutritionPlan\Entity\Segment::class, $segment);

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem);

        $actual = SegmentReadModel::fromSegment($segment);

        $this->assertSame($segment->id, $actual->id);
        $this->assertSame($segment->position, $actual->position);
        $this->assertInstanceOf(CheckpointReadModel::class, $actual->startCheckpoint);
        $this->assertInstanceOf(CheckpointReadModel::class, $actual->endCheckpoint);
        $this->assertSame($segment->getDistance()->value, $actual->distance);
        $this->assertSame($segment->getAscent()->value, $actual->ascent);
        $this->assertSame($segment->getDescent()->value, $actual->descent);
        $this->assertCount(1, $actual->nutritionItems);

        $nutritionItemReadModel = $actual->nutritionItems[0];
        $this->assertInstanceOf(NutritionItemReadModel::class, $nutritionItemReadModel);
        $this->assertSame('abcde', $nutritionItemReadModel->id);
        $this->assertSame('externalReference', $nutritionItemReadModel->externalReference);
        $this->assertSame('name', $nutritionItemReadModel->name);
        $this->assertSame(40, $nutritionItemReadModel->carbs);
        $this->assertSame(2, $nutritionItemReadModel->quantity);
    }
}
