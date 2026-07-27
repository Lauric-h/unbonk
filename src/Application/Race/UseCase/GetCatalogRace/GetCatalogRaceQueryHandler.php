<?php

namespace App\Application\Race\UseCase\GetCatalogRace;

use App\Application\Race\Port\RaceCatalogPort;
use App\Application\Race\ReadModel\CatalogRaceReadModel;
use App\Application\Race\UseCase\GetCatalogEvent\GetCatalogEventQuery;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetCatalogRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RaceCatalogPort $raceCatalogPort)
    {
    }

    public function __invoke(GetCatalogRAceQuery $query): CatalogRaceReadModel
    {
        return $this->raceCatalogPort->getRace($query->eventId, $query->raceId);
    }
}