<?php

namespace App\Application\NutritionPlan\UseCase\ListRunnerRaces;

use App\Application\NutritionPlan\ReadModel\RunnerRaceReadModel;
use App\Domain\NutritionPlan\Entity\RunnerRace;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListRunnerRacesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private RunnerRacesCatalog $racesCatalog,
    ) {
    }

    /**
     * @return RunnerRaceReadModel[]
     */
    public function __invoke(ListRunnerRacesQuery $query): array
    {
        $races = $this->racesCatalog->findByRunnerId($query->userId);

        return array_map(
            static fn (RunnerRace $race) => RunnerRaceReadModel::fromRunnerRace($race),
            $races
        );
    }
}
