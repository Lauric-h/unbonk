<?php

namespace App\Application\Race\UseCase\GetRace;

use App\Domain\Race\Repository\RacesCatalog;
use App\Infrastructure\Shared\Bus\QueryHandlerInterface;
use App\UI\Http\Rest\Race\View\RaceReadModel;

final class GetRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(GetRaceQuery $query): RaceReadModel
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($query->id, $query->runnerId);

        return RaceReadModel::fromRace($race);
    }
}
