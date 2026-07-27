<?php

namespace App\Application\Race\UseCase\GetRunnerRace;

use App\Application\Race\Exception\RunnerRaceAccessDeniedException;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Application\Race\UseCase\GetRunnerRace\ReadModel\RunnerRaceDetailReadModel;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetRunnerRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RunnerRaceReaderInterface $runnerRaceReader)
    {
    }

    public function __invoke(GetRunnerRaceQuery $query): RunnerRaceDetailReadModel
    {
        $race = $this->runnerRaceReader->get($query->raceId);

        if ($query->runnerId !== $race->runnerId) {
            throw new RunnerRaceAccessDeniedException();
        }

        return $race;
    }
}
