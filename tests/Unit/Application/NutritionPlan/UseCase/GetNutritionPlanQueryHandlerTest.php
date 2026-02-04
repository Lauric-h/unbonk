<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\CheckpointReadModel;
use App\Application\NutritionPlan\ReadModel\ImportedRaceReadModel;
use App\Application\NutritionPlan\ReadModel\NutritionItemReadModel;
use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentReadModel;
use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQueryHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class GetNutritionPlanQueryHandlerTest extends TestCase
{
    public function testGetNutritionPlan(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new GetNutritionPlanQueryHandler($repository);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $id = $nutritionPlan->id;
        $query = new GetNutritionPlanQuery($id);

        // Add a nutrition item to the first segment
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        $nutritionItem = new NutritionItem(
            'item-id',
            'externalRef',
            'Gel',
            new Carbs(25),
            new Quantity(2),
            new Calories(100)
        );
        $segment->addNutritionItem($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($nutritionPlan);

        $result = ($handler)($query);

        $this->assertInstanceOf(NutritionPlanReadModel::class, $result);
        $this->assertSame($nutritionPlan->id, $result->id);
        $this->assertSame($nutritionPlan->runnerId, $result->runnerId);

        // Check imported race
        $this->assertInstanceOf(ImportedRaceReadModel::class, $result->importedRace);
        $this->assertSame('Test Race', $result->importedRace->name);
        $this->assertCount(3, $result->importedRace->checkpoints);

        // Check segments
        $this->assertCount(2, $result->segments);

        // First segment should have the nutrition item
        $firstSegment = $result->segments[0];
        $this->assertInstanceOf(SegmentReadModel::class, $firstSegment);
        $this->assertInstanceOf(CheckpointReadModel::class, $firstSegment->startCheckpoint);
        $this->assertInstanceOf(CheckpointReadModel::class, $firstSegment->endCheckpoint);
        $this->assertCount(1, $firstSegment->nutritionItems);

        $nutritionItemReadModel = $firstSegment->nutritionItems[0];
        $this->assertInstanceOf(NutritionItemReadModel::class, $nutritionItemReadModel);
        $this->assertSame('item-id', $nutritionItemReadModel->id);
        $this->assertSame('Gel', $nutritionItemReadModel->name);
        $this->assertSame(25, $nutritionItemReadModel->carbs);
        $this->assertSame(2, $nutritionItemReadModel->quantity);
        $this->assertSame(100, $nutritionItemReadModel->calories);
    }
}
