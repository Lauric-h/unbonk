<?php

namespace App\Application\NutritionPlan\UseCase\ListUserRaces;

use App\Application\NutritionPlan\ReadModel\ImportedRaceReadModel;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListUserRacesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private RacesCatalog $racesCatalog,
    ) {
    }

    /**
     * @return ImportedRaceReadModel[]
     */
    public function __invoke(ListUserRacesQuery $query): array
    {
        $races = $this->racesCatalog->findByRunnerId($query->userId);

        return array_map(
            static fn (ImportedRace $race) => ImportedRaceReadModel::fromImportedRace($race),
            $races
        );
    }
}
