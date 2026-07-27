<?php

namespace App\Application\Race\UseCase\ListRunnerRaces;

use App\Application\Race\ReadModel\RunnerRaceReadModel;
use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;
use App\Domain\Shared\Bus\QueryHandlerInterface;
use App\UI\Http\Rest\NutritionPlan\Controller\Race\DeleteUserRaceController;

final readonly class ListRunnerRacesQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RunnerRaceRepositoryInterface $raceRepository)
    {
    }

    /**
     * @return RunnerRaceReadModel[]
     */
    public function __invoke(ListRunnerRacesQuery $query): array
    {
        $races = $this->raceRepository->findByRunnerId($query->runnerId);

        return array_map(
            static fn (RunnerRace $runnerRace) => new RunnerRaceReadModel(
               id: $runnerRace->getId(),
               name: $runnerRace->getName(),
               eventName: $runnerRace->getEventName(),
               startDateTime: $runnerRace->getStartDateTime(),
               distance: $runnerRace->getDistance(),
               ascent: $runnerRace->getAscent(),
               descent: $runnerRace->getDescent(),
            ),
            $races,
        );
    }
}