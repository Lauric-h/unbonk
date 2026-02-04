<?php

namespace App\Application\NutritionPlan\ReadModel\External;

use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;

final readonly class ExternalEventReadModel
{
    /**
     * @param ExternalRaceListItemReadModel[] $races
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public \DateTimeImmutable $date,
        public ?string $url,
        public array $races = [],
    ) {
    }

    public static function fromDTO(ExternalEventDTO $dto): self
    {
        return new self(
            id: $dto->id,
            name: $dto->name,
            location: $dto->location,
            date: $dto->date,
            url: $dto->url,
            races: array_map(
                static fn (ExternalRaceDTO $raceDTO) => ExternalRaceListItemReadModel::fromDTO($raceDTO),
                $dto->races,
            ),
        );
    }
}
