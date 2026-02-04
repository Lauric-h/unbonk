<?php

namespace App\Domain\NutritionPlan\DTO;

final readonly class ExternalEventDTO
{
    /**
     * @param ExternalRaceDTO[] $races
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
}
