<?php

namespace App\UI\Http\Rest\Race\View;

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
            $metricsFromStart->estimatedTimeInMinutes,
            $metricsFromStart->distance,
            $metricsFromStart->elevationGain,
            $metricsFromStart->elevationLoss,
        );
    }
}
