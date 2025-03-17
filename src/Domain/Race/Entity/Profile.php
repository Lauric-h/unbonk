<?php

namespace App\Domain\Race\Entity;

use App\Domain\Race\Exception\DistanceCannotBeNegativeException;
use App\Domain\Race\Exception\ElevationValueCannotBeNegativeException;

final readonly class Profile
{
    public function __construct(
        public int $distance,
        public int $elevationGain,
        public int $elevationLoss,
    ) {
        if ($elevationGain < 0) {
            throw new ElevationValueCannotBeNegativeException('elevationGain', $elevationGain);
        }
        if ($elevationLoss < 0) {
            throw new ElevationValueCannotBeNegativeException('elevationLoss', $elevationLoss);
        }
        if ($distance < 0) {
            throw new DistanceCannotBeNegativeException($distance);
        }
    }
}
