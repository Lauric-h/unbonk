<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommand;
use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class DeleteNutritionCommandHandlerTest extends TestCase
{
    public function testDeleteNutritionItem(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new DeleteNutritionItemCommandHandler($repository);

        $nutritionPlanId = 'npId';
        $segmentId = 'sId';
        $nutritionItemId = 'nId';

        $nutritionPlan = new NutritionPlan(
            'npId',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'sId',
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
            id: 'nId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            segment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem);

        $nutritionItem2 = new NutritionItem(
            id: 'fghij',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            segment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem2);

        $repository->expects($this->once())
            ->method('getByNutritionPlanAndId')
            ->with($nutritionPlanId, $segmentId)
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add');

        $command = new DeleteNutritionItemCommand($nutritionPlanId, $segmentId, $nutritionItemId);
        ($handler)($command);

        $this->assertCount(1, $segment->nutritionItems);
        $this->assertEquals('fghij', $segment->nutritionItems->first()->id);
    }
}
