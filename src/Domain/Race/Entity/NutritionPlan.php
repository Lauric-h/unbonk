<?php

namespace App\Domain\Race\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class NutritionPlan
{
    /**
     * @param Collection<int, NutritionSegment> $nutritionSegments
     */
    public function __construct(
        public string $id,
        public Race $race,
        public string $runnerId,
        public Collection $nutritionSegments = new ArrayCollection(),
    ) {
    }
}
