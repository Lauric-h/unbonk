<?php

namespace App\Domain\Race\Entity;

use App\Domain\Race\Exception\DistanceCannotBeNegativeException;
use App\Domain\Race\Exception\ElevationValueCannotBeNegativeException;
use App\Domain\Race\Exception\EstimatedTimeCannotBeNegativeException;

final readonly class MetricsFromStart
{
    public function __construct(
        public int $estimatedTimeInMinutes,
        public int $distance,
        public int $elevationGain,
        public int $elevationLoss,
    ) {
        if ($this->estimatedTimeInMinutes < 0) {
            throw new EstimatedTimeCannotBeNegativeException($estimatedTimeInMinutes);
        }
        if ($elevationGain < 0) {
            throw new ElevationValueCannotBeNegativeException('elevationGain', $elevationGain);
        }
        if ($elevationLoss < 0) {
            throw new ElevationValueCannotBeNegativeException('elevationLoss', $elevationLoss);
        }
        if ($distance <= 0) {
            throw new DistanceCannotBeNegativeException($distance);
        }
    }
}
