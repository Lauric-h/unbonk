<?php

namespace App\UI\Http\Rest\Race\Request;

final readonly class UpdateRaceRequest
{
    public function __construct(
        public string $date,
        public string $name,
        public int $distance,
        public int $elevationGain,
        public int $elevationLoss,
        public string $city,
        public string $postalCode,
    ) {
    }
}
