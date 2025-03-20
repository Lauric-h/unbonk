<?php

namespace App\Application\Race\UseCase\ListRace;

use App\Infrastructure\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ListRaceQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
