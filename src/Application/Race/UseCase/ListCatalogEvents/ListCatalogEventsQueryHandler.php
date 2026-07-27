<?php

namespace App\Application\Race\UseCase\ListCatalogEvents;

use App\Application\Race\Port\RaceCatalogPort;
use App\Application\Race\ReadModel\CatalogEventReadModel;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListCatalogEventsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RaceCatalogPort $raceCatalogPort)
    {
    }

    /**
     * @return CatalogEventReadModel[]
     */
    public function __invoke(ListCatalogEventsQuery $query): array
    {
        return $this->raceCatalogPort->listAllEvents();
    }
}