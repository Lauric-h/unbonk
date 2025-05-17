<?php

namespace App\Application\Race\ReadModel;

use App\Domain\Race\Entity\MetricsFromStart;

final readonly class MetricsFromStartReadModel
{
    public function __construct(
        public int $estimatedTimeInMinutes,
        public int $distance,
        public int $elevationGain,
        public int $elevationLoss,
    ) {
    }

    public static function fromDomain(MetricsFromStart $metricsFromStart): self
    {
        return new self(
            $metricsFromStart->estimatedTimeInMinutes->minutes,
            $metricsFromStart->distance->value,
            $metricsFromStart->ascent->value,
            $metricsFromStart->descent->value,
        );
    }
}
