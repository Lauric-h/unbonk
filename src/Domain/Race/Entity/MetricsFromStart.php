<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

final readonly class MetricsFromStart
{
    public function __construct(
        public Duration $estimatedTimeInMinutes,
        public Distance $distance,
        public Ascent $ascent,
        public Descent $descent,
    ) {
    }

    public static function create(int $duration, int $distance, int $ascent, int $descent): self
    {
        return new self(
            new Duration($duration),
            new Distance($distance),
            new Ascent($ascent),
            new Descent($descent),
        );
    }

    public function equals(MetricsFromStart $metrics): bool
    {
        return $this->distance->value === $metrics->distance->value
            && $this->ascent->value === $metrics->ascent->value
            && $this->descent->value === $metrics->descent->value;
    }
}
