<?php

namespace App\Application\NutritionPlan\ReadModel\Catalog;

final readonly class CatalogEventReadModel
{
    /**
     * @param CatalogRaceReadModel[] $races
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