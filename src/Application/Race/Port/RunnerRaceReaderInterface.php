<?php

namespace App\Application\Race\Port;


use App\Application\Race\UseCase\GetMyRace\ReadModel\RunnerRaceDetailReadModel;
use App\Application\Race\UseCase\GetMyRaces\ReadModel\RunnerRaceSummaryReadModel;

interface RunnerRaceReaderInterface
{
    public function get(string $runnerRaceId): RunnerRaceDetailReadModel;

    /**
     * @return RunnerRaceSummaryReadModel[]
     */
    public function listForRunner(string $runnerId): array;
}