<?php

namespace App\Application\NutritionPlan\ReadModel\Catalog;

final readonly class CatalogRaceReadModel
{
    /**
     * @param CatalogAidStationReadModel[] $aidStations
     */
    public function __construct(
        public string $id,
        public string $eventId,
        public string $eventName,
        public string $name,
        public int $distanceInMeters,
        public int $ascent,
        public int $descent,
        public \DateTimeImmutable $startDateTime,
        public ?int $maxDurationInMinutes,
        public ?string $url,
        public string $startLocation,
        public string $finishLocation,
        public array $aidStations = [],
    ) {
    }
}