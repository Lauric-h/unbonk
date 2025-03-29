<?php

namespace App\Application\NutritionPlan\DTO;

use App\Domain\NutritionPlan\Entity\SegmentPoint;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

final class PointDTO
{
    public function __construct(
        public string $externalReference,
        public int $distance,
        public int $estimatedDuration,
        public int $ascent,
        public int $descent,
    ) {
    }

    public function toSegmentPoint(): SegmentPoint
    {
        return new SegmentPoint(
            $this->externalReference,
            new Distance($this->distance),
            new Duration($this->estimatedDuration),
            new Ascent($this->ascent),
            new Descent($this->descent),
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): PointDTO
    {
        return new self(
            $data['id'],
            $data['distance'],
            $data['estimatedDuration'],
            $data['ascent'],
            $data['descent'],
        );
    }
}
