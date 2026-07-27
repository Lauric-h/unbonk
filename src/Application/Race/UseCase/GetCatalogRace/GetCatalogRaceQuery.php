<?php

namespace App\Application\Race\UseCase\GetCatalogRace;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class GetCatalogRaceQuery implements QueryInterface
{
    public function __construct(
        public string $eventId,
        public string $raceId
    ) {
    }
}