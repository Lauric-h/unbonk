<?php

namespace App\Domain\NutritionPlan\DTO;

final readonly class ExternalRaceDTO
{
    /**
     * @param ExternalAidStationDTO[] $aidStations
     */
    public function __construct(
        public string $id,
        public string $eventId,
        public string $name,
        public int $distance,
        public int $ascent,
        public int $descent,
        public \DateTimeImmutable $startDateTime,
        public ?string $url,
        public string $startLocation,
        public string $finishLocation,
        public array $aidStations = [],
    ) {
    }
}
