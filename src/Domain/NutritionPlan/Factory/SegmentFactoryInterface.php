<?php

namespace App\Domain\NutritionPlan\Factory;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Entity\SegmentPoint;
use App\Domain\Shared\Entity\Carbs;
use Doctrine\Common\Collections\Collection;

interface SegmentFactoryInterface
{
    /**
     * @param Collection<int, NutritionItem> $nutritionItems
     */
    public function createWithNutritionData(SegmentPoint $startPoint, SegmentPoint $finishPoint, Carbs $carbs, NutritionPlan $nutritionPlan, Collection $nutritionItems): Segment;

    public function createFromPoints(SegmentPoint $startPoint, SegmentPoint $finishPoint, NutritionPlan $nutritionPlan): Segment;
}
