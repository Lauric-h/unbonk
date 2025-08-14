<?php

namespace App\UI\Http\Web\Race\Form\AddCheckpoint;

use App\Domain\Race\Entity\CheckpointType;
use Symfony\Component\Validator\Constraints as Assert;

final class AddCheckpointModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $name = null,
        #[Assert\NotBlank]
        public ?string $location = null,
        #[Assert\NotBlank]
        public ?CheckpointType $checkpointType = null,
        #[Assert\PositiveOrZero]
        public ?int $estimatedTimeInMinutes = null,
        #[Assert\Positive]
        public ?int $distance = null,
        #[Assert\PositiveOrZero]
        public ?int $ascent = null,
        #[Assert\PositiveOrZero]
        public ?int $descent = null,
    ) {
    }
}
