<?php

namespace App\Application\NutritionPlan\Port;

use App\Application\NutritionPlan\Exception\CatalogEventNotFoundException;
use App\Application\NutritionPlan\Exception\CatalogRaceNotFoundException;
use App\Application\NutritionPlan\ReadModel\Catalog\CatalogEventReadModel;
use App\Application\NutritionPlan\ReadModel\Catalog\CatalogRaceReadModel;

interface RaceCatalogPort
{
    /**
     * @return CatalogEventReadModel[]
     */
    public function listAllEvents(): array;

    /**
     * @throws CatalogEventNotFoundException
     */
    public function getEvent(string $eventId): CatalogEventReadModel;

    /**
     * @throws CatalogEventNotFoundException
     * @throws CatalogRaceNotFoundException
     */
    public function getRace(string $eventId, string $raceId): CatalogRaceReadModel;
}
