<?php

namespace App\Application\Race\Port;


use App\Application\Race\UseCase\GetRunnerRace\ReadModel\RunnerRaceDetailReadModel;

interface RunnerRaceReaderInterface
{
    public function get(string $runnerRaceId): RunnerRaceDetailReadModel;
}