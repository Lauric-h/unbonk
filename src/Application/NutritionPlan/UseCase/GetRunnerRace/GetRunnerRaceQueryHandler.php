<?php

namespace App\Application\NutritionPlan\UseCase\GetRunnerRace;

use App\Application\NutritionPlan\ReadModel\RunnerRaceReadModel;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetRunnerRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RunnerRacesCatalog $racesCatalog)
    {
    }

    public function __invoke(GetRunnerRaceQuery $query): RunnerRaceReadModel
    {
        $race = $this->racesCatalog->get($query->id);

        return RunnerRaceReadModel::fromRunnerRace($race);
    }
}
