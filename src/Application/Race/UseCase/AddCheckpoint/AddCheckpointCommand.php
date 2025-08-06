<?php

namespace App\Application\Race\UseCase\AddCheckpoint;

use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class AddCheckpointCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string         $name,
        #[Assert\NotBlank]
        public string         $location,
        #[Assert\Choice(choices: [CheckpointType::AidStation, CheckpointType::Intermediate])]
        public CheckpointType $checkpointType,
        #[Assert\PositiveOrZero]
        public int            $estimatedTimeInMinutes,
        #[Assert\PositiveOrZero]
        public int            $distance,
        #[Assert\PositiveOrZero]
        public int            $ascent,
        #[Assert\PositiveOrZero]
        public int            $descent,
        #[Assert\Uuid]
        public string         $raceId,
        #[Assert\Uuid]
        public string         $runnerId,
    ) {
    }
}
