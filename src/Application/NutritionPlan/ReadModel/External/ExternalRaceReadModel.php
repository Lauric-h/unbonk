<?php

namespace App\Application\NutritionPlan\ReadModel\External;

use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;

final readonly class ExternalRaceReadModel
{
    /**
     * @param ExternalAidStationReadModel[] $aidStations
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

    public static function fromDTO(ExternalRaceDTO $dto): self
    {
        return new self(
            id: $dto->id,
            eventId: $dto->eventId,
            name: $dto->name,
            distance: $dto->distance,
            ascent: $dto->ascent,
            descent: $dto->descent,
            startDateTime: $dto->startDateTime,
            url: $dto->url,
            startLocation: $dto->startLocation,
            finishLocation: $dto->finishLocation,
            aidStations: array_map(
                static fn (ExternalAidStationDTO $dto) => ExternalAidStationReadModel::fromDTO($dto),
                $dto->aidStations,
            )
        );
    }
}
