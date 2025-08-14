<?php

namespace App\UI\Http\Web\Race\Form\UpdateCheckpoint;

use App\Application\Race\ReadModel\CheckpointReadModel;
use App\Domain\Race\Entity\CheckpointType;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateCheckpointModel
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

    public static function fromCheckpoint(CheckpointReadModel $checkpoint): self
    {
        return new self(
            $checkpoint->name,
            $checkpoint->location,
            CheckpointType::from($checkpoint->checkpointType),
            $checkpoint->metricsFromStart->estimatedTimeInMinutes,
            $checkpoint->metricsFromStart->distance,
            $checkpoint->metricsFromStart->ascent,
            $checkpoint->metricsFromStart->descent,
        );
    }
}
