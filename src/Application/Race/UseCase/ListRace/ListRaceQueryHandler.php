<?php

namespace App\Application\Race\UseCase\ListRace;

use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;
use App\UI\Http\Rest\Race\View\ListRaceReadModel;

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
