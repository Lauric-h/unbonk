<?php

namespace App\UI\Http\Web\Race\Form\AddCheckpoint;

use Symfony\Component\Validator\Constraints as Assert;

use App\Domain\Race\Entity\CheckpointType;

final class AddCheckpointModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string         $name = null,
        #[Assert\NotBlank]
        public ?string         $location = null,
        #[Assert\NotBlank]
        public ?CheckpointType $checkpointType = null,
        #[Assert\PositiveOrZero]
        public ?int            $estimatedTimeInMinutes = null,
        #[Assert\Positive]
        public ?int            $distance = null,
        #[Assert\PositiveOrZero]
        public ?int            $ascent = null,
        #[Assert\PositiveOrZero]
        public ?int            $descent = null,
    ) {
    }
}