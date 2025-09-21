<?php

namespace App\UI\Http\Rest\Race\Request;

final class CreateRaceRequest
{
    public function __construct(
        public string $date,
        public string $name,
        public int    $distance,
        public int    $ascent,
        public int    $descent,
        public string $city,
        public string $postalCode,
    ) {
    }
}
