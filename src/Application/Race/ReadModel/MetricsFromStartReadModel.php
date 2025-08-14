<?php

namespace App\Application\Race\ReadModel;

use App\Domain\Race\Entity\MetricsFromStart;

final readonly class MetricsFromStartReadModel
{
    public function __construct(
        public int $estimatedTimeInMinutes,
        public int $distance,
        public int $ascent,
        public int $descent,
    ) {
    }

    public static function fromDomain(MetricsFromStart $metricsFromStart): self
    {
        return new self(
            $metricsFromStart->estimatedTimeInMinutes,
            $metricsFromStart->distance,
            $metricsFromStart->ascent,
            $metricsFromStart->descent,
        );
    }
}
