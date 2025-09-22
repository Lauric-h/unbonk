<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\Segment;

interface SegmentsCatalog
{
    public function get(string $id): Segment;

    public function add(Segment $segment): void;

    public function getByNutritionPlanAndId(string $nutritionPlanId, string $segmentId): Segment;
}
