<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class NutritionPlanTest extends TestCase
{
    public function testCreateFromRunnerRaceCreatesSegmentPlans(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $this->assertCount(2, $nutritionPlan->getSegmentPlans());

        $firstSegmentPlan = $this->getFirstSegmentPlan($nutritionPlan);
        $firstSegment = $nutritionPlan->runnerRace->segments()->first();

        $this->assertSame($firstSegment, $firstSegmentPlan->segment);
    }

    public function testGetSegmentPlanBySegmentId(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $this->assertSame(
            $segmentPlan,
            $nutritionPlan->getSegmentPlanBySegmentId($segmentPlan->segment->id)
        );
        $this->assertNull($nutritionPlan->getSegmentPlanBySegmentId('missing-segment-id'));
    }

    public function testRemoveSegmentPlanRemovesPlan(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $nutritionPlan->removeSegmentPlan($segmentPlan->segment->id);

        $this->assertCount(1, $nutritionPlan->getSegmentPlans());
        $this->assertNull($nutritionPlan->getSegmentPlanBySegmentId($segmentPlan->segment->id));
    }

    public function testRemoveSegmentPlanThrowsExceptionForMissingPlan(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Segment plan for segment missing-segment-id not found');

        $nutritionPlan->removeSegmentPlan('missing-segment-id');
    }

    public function testGetTotalCarbsSumsSegmentItems(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $segmentPlan->addItem(new NutritionItem(
            id: 'item-id',
            segmentNutritionPlan: $segmentPlan,
            foodItemId: 'food-id',
            quantity: new Quantity(2),
            carbs: new Carbs(25),
        ));

        $this->assertSame(25, $nutritionPlan->getTotalCarbs());
    }

    private function getFirstSegmentPlan(NutritionPlan $nutritionPlan): SegmentNutritionPlan
    {
        $segmentPlan = $nutritionPlan->getSegmentPlans()->first();
        $this->assertInstanceOf(SegmentNutritionPlan::class, $segmentPlan);

        return $segmentPlan;
    }
}
