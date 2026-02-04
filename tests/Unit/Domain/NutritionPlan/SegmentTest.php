<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class SegmentTest extends TestCase
{
    private function getTestSegment(): Segment
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        return $segment;
    }

    public function testGetDistanceCalculatesFromCheckpoints(): void
    {
        $segment = $this->getTestSegment();

        // Start checkpoint is at 0, aid station is at 25000
        $this->assertSame(25000, $segment->getDistance()->value);
    }

    public function testGetAscentCalculatesFromCheckpoints(): void
    {
        $segment = $this->getTestSegment();

        // Start checkpoint is at 0, aid station is at 1000
        $this->assertSame(1000, $segment->getAscent()->value);
    }

    public function testGetDescentCalculatesFromCheckpoints(): void
    {
        $segment = $this->getTestSegment();

        // Start checkpoint is at 0, aid station is at 750
        $this->assertSame(750, $segment->getDescent()->value);
    }

    public function testAddNutritionItem(): void
    {
        $segment = $this->getTestSegment();

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );

        $segment->addNutritionItem($nutritionItem);

        $this->assertCount(1, $segment->getNutritionItems());
        $this->assertSame($nutritionItem, $segment->getNutritionItems()->first());
    }

    public function testAddNutritionItemWithExistingItemRemovesAndReplace(): void
    {
        $segment = $this->getTestSegment();

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem);

        $carbs = new Carbs(60);
        $quantity = new Quantity(4);
        $calories = new Calories(300);

        $newNutritionItem = new NutritionItem(
            id: 'itemId2',
            externalReference: 'externalReference',
            name: 'name2',
            carbs: $carbs,
            quantity: $quantity,
            calories: $calories
        );

        $segment->addNutritionItem($newNutritionItem);

        $this->assertCount(1, $segment->getNutritionItems());
        $this->assertSame('itemId2', $segment->getNutritionItems()->first()->id);
        $this->assertSame('externalReference', $segment->getNutritionItems()->first()->externalReference);
        $this->assertSame('name2', $segment->getNutritionItems()->first()->name);
        $this->assertSame($carbs, $segment->getNutritionItems()->first()->carbs);
        $this->assertSame($quantity, $segment->getNutritionItems()->first()->quantity);
        $this->assertSame($calories, $segment->getNutritionItems()->first()->calories);
    }

    public function testGetNutritionItemByExternalReference(): void
    {
        $segment = $this->getTestSegment();

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem);

        $this->assertSame($nutritionItem, $segment->getNutritionItemByExternalReference('externalReference'));
    }

    public function testGetNutritionItemByExternalReferenceReturnsNull(): void
    {
        $segment = $this->getTestSegment();

        $this->assertNotInstanceOf(NutritionItem::class, $segment->getNutritionItemByExternalReference('abcde'));
    }

    public function testRemoveNutritionItemNotFoundThrowsException(): void
    {
        $segment = $this->getTestSegment();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Segment does not have NutritionItem with id abcde');
        $segment->removeNutritionItem('abcde');
    }

    public function testRemoveNutritionItem(): void
    {
        $segment = $this->getTestSegment();

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem);

        $nutritionItem2 = new NutritionItem(
            id: 'fghij',
            externalReference: 'externalReference2',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem2);

        $segment->removeNutritionItem('abcde');

        $this->assertCount(1, $segment->getNutritionItems());
        $this->assertSame('fghij', $segment->getNutritionItems()->first()->id);
    }

    public function testGetNutritionItemById(): void
    {
        $segment = $this->getTestSegment();

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem);

        $this->assertSame($nutritionItem, $segment->getNutritionItemById('itemId'));
    }

    public function testGetNutritionItemByIdReturnsNull(): void
    {
        $segment = $this->getTestSegment();

        $this->assertNotInstanceOf(NutritionItem::class, $segment->getNutritionItemById('abcde'));
    }
}
