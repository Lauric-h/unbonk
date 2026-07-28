<?php

namespace App\Application\Race\UseCase\GetMyRace;

use App\Application\Race\Exception\RunnerRaceAccessDeniedException;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Application\Race\UseCase\GetMyRace\ReadModel\RunnerRaceDetailReadModel;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class GetMyRaceQueryHandler implements QueryHandlerInterface
{
    public function __construct(private RunnerRaceReaderInterface $runnerRaceReader)
    {
    }

    public function __invoke(GetMyRaceQuery $query): RunnerRaceDetailReadModel
    {
        $race = $this->runnerRaceReader->get($query->raceId);

        if ($query->runnerId !== $race->runnerId) {
            throw new RunnerRaceAccessDeniedException();
        }

        return $race;
    }
}
