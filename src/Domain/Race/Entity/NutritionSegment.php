<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Carbs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class NutritionSegment
{
    /**
     * @param Collection<int, NutritionItem> $nutritionItems
     */
    public function __construct(
        public string $id,
        public int $segmentPosition,
        public Carbs $carbsTarget,
        public NutritionPlan $nutritionPlan,
        public Collection $nutritionItems = new ArrayCollection(),
    ) {
    }

    public function getSegment(): Segment
    {
        $segment = $this->nutritionPlan->race->getSegmentByPosition($this->segmentPosition);
        // @TODO error
        if (null === $segment) {
            throw new \DomainException('NutritionSegment without Segment');
        }

        return $segment;
    }
}
