<?php

namespace App\Application\Race\UseCase\GetCatalogEvent;

use App\Application\Race\Port\RaceCatalogPort;
use App\Application\Race\ReadModel\CatalogEventReadModel;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetCatalogEventQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RaceCatalogPort $raceCatalogPort)
    {
    }

    public function __invoke(GetCatalogEventQuery $query): CatalogEventReadModel
    {
        return $this->raceCatalogPort->getEvent($query->id);
    }
}