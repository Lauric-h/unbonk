<?php

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\Race\Entity\NutritionItem;
use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Entity\Quantity;
use App\Domain\Race\Entity\Segment;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class SegmentTest extends TestCase
{
    public function testAddNutritionItem(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: null
        );

        $segment->addNutritionItem($nutritionItem);

        $this->assertCount(1, $segment->nutritionItems);
        $this->assertSame($nutritionItem, $segment->nutritionItems->first());
    }

    public function testAddNutritionItemWithExistingItemRemovesAndReplace(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem);

        $carbs = new Carbs(60);
        $quantity = new Quantity(4);
        $calories = new Calories(300);

        $newNutritionItem = new NutritionItem(
            id: 'itemId2',
            externalReference: 'externalReference',
            name: 'name2',
            carbs: $carbs,
            quantity: $quantity,
            nutritionSegment: $segment,
            calories: $calories
        );

        $segment->addNutritionItem($newNutritionItem);

        $this->assertCount(1, $segment->nutritionItems);
        $this->assertSame('itemId2', $segment->nutritionItems->first()->id);
        $this->assertSame('externalReference', $segment->nutritionItems->first()->externalReference);
        $this->assertSame('name2', $segment->nutritionItems->first()->name);
        $this->assertSame($carbs, $segment->nutritionItems->first()->carbs);
        $this->assertSame($quantity, $segment->nutritionItems->first()->quantity);
        $this->assertSame($calories, $segment->nutritionItems->first()->calories);
    }

    public function testGetNutritionItemByExternalReference(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem);

        $this->assertSame($nutritionItem, $segment->getNutritionItemByExternalReference('externalReference'));
    }

    public function testGetNutritionItemByExternalReferenceReturnsNull(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $this->assertNotInstanceOf(NutritionItem::class, $segment->getNutritionItemByExternalReference('abcde'));
    }

    public function testRemoveNutritionItemNotFoundThrowsException(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Segment does not have NutritionItem with id abcde');
        $segment->removeNutritionItem('abcde');
    }

    public function testRemoveNutritionItem(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem);

        $nutritionItem2 = new NutritionItem(
            id: 'fghij',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem2);

        $segment->removeNutritionItem('abcde');

        $this->assertCount(1, $segment->nutritionItems);
        $this->assertSame('fghij', $segment->nutritionItems->first()->id);
    }

    public function testGetNutritionItemById(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $nutritionItem = new NutritionItem(
            id: 'itemId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem);

        $this->assertSame($nutritionItem, $segment->getNutritionItemById('itemId'));
    }

    public function testGetNutritionItemByIdReturnsNull(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $this->assertNotInstanceOf(NutritionItem::class, $segment->getNutritionItemById('abcde'));
    }
}
