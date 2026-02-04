<?php

namespace App\Application\NutritionPlan\ReadModel\External;

use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;

final readonly class ExternalAidStationReadModel
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

    public static function fromDTO(ExternalAidStationDTO $dto): self
    {
        return new self(
            id: $dto->id,
            name: $dto->name,
            location: $dto->location,
            distanceFromStart: $dto->distanceFromStart,
            ascentFromStart: $dto->ascentFromStart,
            descentFromStart: $dto->descentFromStart,
            cutoffTime: $dto->cutoffTime,
            assistanceAllowed: $dto->assistanceAllowed,
        );
    }
}
