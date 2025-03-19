<?php

namespace App\Application\Race\UseCase\GetRace;

use App\Infrastructure\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRaceQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
