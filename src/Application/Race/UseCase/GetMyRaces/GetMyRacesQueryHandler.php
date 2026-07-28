<?php

namespace App\Application\Race\UseCase\GetMyRaces;

use App\Application\Race\UseCase\GetMyRaces\ReadModel\RunnerRaceSummaryReadModel;
use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetMyRacesQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RunnerRaceRepositoryInterface $raceRepository)
    {
    }

    /**
     * @return RunnerRaceSummaryReadModel[]
     */
    public function __invoke(GetMyRacesQuery $query): array
    {
        $races = $this->raceRepository->findByRunnerId($query->runnerId);

        return array_map(
            static fn (RunnerRace $runnerRace) => new RunnerRaceSummaryReadModel(
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