<?php

namespace App\Application\NutritionPlan\ReadModel\External;

use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;

final readonly class ExternalRaceListItemReadModel
{
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
        );
    }
}
