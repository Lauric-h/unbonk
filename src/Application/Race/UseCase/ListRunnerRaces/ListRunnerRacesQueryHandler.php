<?php

namespace App\Application\Race\UseCase\ListRunnerRaces;

use App\Domain\Race\Repository\RunnerRaceRepositoryInterface;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListRunnerRacesQueryHandler implements QueryHandlerInterface
{
//    public function __construct(private RunnerRaceRepositoryInterface $raceRepository)
//    {
//    }

    /**
     * @return array<mixed, mixed>
     */
    public function __invoke(ListRunnerRacesQuery $query): array
    {
        return []; // @TODO
    }
}