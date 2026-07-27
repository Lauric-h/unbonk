<?php

namespace App\Application\Race\UseCase\GetCatalogRace;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetCatalogRaceQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $eventId,
        #[Assert\Uuid]
        public string $raceId
    ) {
    }
}