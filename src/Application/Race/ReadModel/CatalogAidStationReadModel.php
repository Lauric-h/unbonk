<?php

namespace App\Application\Race\ReadModel;

final readonly class CatalogAidStationReadModel
{
    public function __construct(
        public string  $id,
        public string  $name,
        public string $location,
        public int     $distanceFromStartInMeters,
        public int     $ascentFromStart,
        public int     $descentFromStart,
        public ?int    $cutoffOffsetInMinutes, // durée depuis le départ, pas une date absolue
        public bool    $assistanceAllowed,
    ) {
    }
}