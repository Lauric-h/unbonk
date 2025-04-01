<?php

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

final class SegmentPoint
{
    public function __construct(
        public string $externalReference,
        public Distance $distance,
        public Duration $estimatedDuration,
        public Ascent $ascent,
        public Descent $descent,
    ) {
    }
}
