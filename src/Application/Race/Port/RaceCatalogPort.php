<?php

namespace App\Application\Race\Port;

use App\Application\Race\Exception\CatalogEventNotFoundException;
use App\Application\Race\Exception\CatalogRaceNotFoundException;
use App\Application\Race\ReadModel\CatalogEventReadModel;
use App\Application\Race\ReadModel\CatalogRaceReadModel;

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
