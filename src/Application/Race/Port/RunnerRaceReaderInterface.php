<?php

namespace App\Application\Race\Port;


use App\Application\Race\UseCase\GetMyRace\ReadModel\RunnerRaceDetailReadModel;

interface RunnerRaceReaderInterface
{
    public function get(string $runnerRaceId): RunnerRaceDetailReadModel;
}