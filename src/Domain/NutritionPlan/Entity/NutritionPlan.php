<?php

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class NutritionPlan
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

    public function getSegmentByStartId(string $startId): ?Segment
    {
        return $this->segments->findFirst(static fn (int $key, Segment $segment) => $segment->startId === $startId);
    }

    /**
     * @param Collection<int, Segment> $segments
     */
    public function replaceAllSegments(Collection $segments): void
    {
        $this->segments->clear();
        foreach ($segments as $segment) {
            $this->segments->add($segment);
        }
    }
}
