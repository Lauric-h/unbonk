<?php

namespace App\Application\NutritionPlan\UseCase\ListUserRaces;

use App\Application\NutritionPlan\ReadModel\RunnerRaceReadModel;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListUserRacesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $racesCatalog,
    ) {
    }

    /**
     * @return RunnerRaceReadModel[]
     */
    public function __invoke(ListUserRacesQuery $query): array
    {
        $races = $this->racesCatalog->findByRunnerId($query->userId);

        return array_map(
            static fn (ImportedRace $race) => RunnerRaceReadModel::fromImportedRace($race),
            $races
        );
    }
}
