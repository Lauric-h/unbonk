<?php

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class NutritionPlan
{
    /**
     * @param Collection<int, Segment> $segments
     */
    public function __construct(
        public string $id,
        public string $raceId,
        public string $runnerId,
        public Collection $segments = new ArrayCollection(),
    ) {
    }
}
