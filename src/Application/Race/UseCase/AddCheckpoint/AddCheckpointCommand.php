<?php

namespace App\Application\Race\UseCase\AddCheckpoint;

use App\Domain\Race\Entity\CheckpointType;
use App\Infrastructure\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class AddCheckpointCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $location,
        #[Assert\Choice(choices: [CheckpointType::AidStation, CheckpointType::Intermediate])]
        public CheckpointType $checkpointType,
        #[Assert\GreaterThanOrEqual(0)]
        public int $estimatedTimeInMinutes,
        #[Assert\GreaterThanOrEqual(0)]
        public int $distance,
        #[Assert\GreaterThanOrEqual(0)]
        public int $elevationGain,
        #[Assert\GreaterThanOrEqual(0)]
        public int $elevationLoss,
        #[Assert\Uuid]
        public string $raceId,
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
