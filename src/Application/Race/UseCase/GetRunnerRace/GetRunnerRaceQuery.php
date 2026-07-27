<?php

namespace App\Application\Race\UseCase\GetRunnerRace;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class GetRunnerRaceQuery implements QueryInterface
{

    public function __construct(
        public string $runnerId,
        public string $raceId,
    ) {
    }
}