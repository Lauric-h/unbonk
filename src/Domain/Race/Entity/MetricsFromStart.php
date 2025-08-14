<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

final readonly class MetricsFromStart
{
    private function __construct(
        public int $estimatedTimeInMinutes,
        public int $distance,
        public int $ascent,
        public int $descent,
    ) {
    }

    public static function create(Duration $duration, Distance $distance, Ascent $ascent, Descent $descent): self
    {
        return new self(
            $duration->minutes,
            $distance->value,
            $ascent->value,
            $descent->value
        );
    }

    public function equals(MetricsFromStart $metrics): bool
    {
        return $this->distance === $metrics->distance
            && $this->ascent === $metrics->ascent
            && $this->descent === $metrics->descent;
    }
}
