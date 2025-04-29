<?php

namespace App\Application\Race\UseCase\ListRace;

use App\Application\Race\ReadModel\ListRaceReadModel;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final class ListRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(ListRaceQuery $query): ListRaceReadModel
    {
        $races = $this->racesCatalog->getAll($query->runnerId);

        return ListRaceReadModel::fromRaces($races);
    }
}
