<?php

namespace App\Application\Race\UseCase\GetRace;

use App\Application\Race\ReadModel\RaceReadModel;
use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(GetRaceQuery $query): RaceReadModel
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($query->id, $query->runnerId);

        return RaceReadModel::fromRace($race);
    }
}
