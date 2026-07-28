<?php

namespace App\Application\Race\UseCase\GetMyRace;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class GetMyRaceQuery implements QueryInterface
{

    public function __construct(
        public string $runnerId,
        public string $raceId,
    ) {
    }
}