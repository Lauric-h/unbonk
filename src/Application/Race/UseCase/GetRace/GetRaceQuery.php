<?php

namespace App\Application\Race\UseCase\GetRace;

use App\Domain\Shared\Bus\QueryInterface;
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
