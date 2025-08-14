<?php

namespace App\Application\Race\UseCase\GetCheckpoint;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetCheckpointQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $raceId,
        #[Assert\Uuid]
        public string $checkpointId,
    ) {
    }
}
