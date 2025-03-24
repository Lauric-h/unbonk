<?php

namespace App\Application\Race\UseCase\UpdateCheckpoint;

use App\Domain\Race\Entity\CheckpointType;
use App\Infrastructure\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateCheckpointCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $location,
        #[Assert\Choice(callback: [CheckpointType::class, 'cases'])]
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
