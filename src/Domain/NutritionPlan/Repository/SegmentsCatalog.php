<?php

namespace App\Domain\NutritionPlan\Repository;

use App\Domain\NutritionPlan\Entity\Segment;

interface SegmentsCatalog
{
    public function get(string $id): Segment;

    public function add(Segment $segment): void;

    public function getByNutritionPlanAndId(string $nutritionPlanId, string $segmentId): Segment;
}
