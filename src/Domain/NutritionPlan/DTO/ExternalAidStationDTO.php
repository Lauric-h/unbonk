<?php

namespace App\Domain\NutritionPlan\DTO;

final readonly class ExternalAidStationDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public int $ascentFromStart,
        public int $descentFromStart,
        public ?\DateTimeImmutable $cutoffTime,
        public bool $assistanceAllowed,
    ) {
    }
}
