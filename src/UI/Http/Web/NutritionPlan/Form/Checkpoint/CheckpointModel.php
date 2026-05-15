<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Form\Checkpoint;

use Symfony\Component\Validator\Constraints as Assert;

final class CheckpointModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $name = null,
        #[Assert\NotBlank]
        public ?string $location = null,
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public ?int $distanceFromStart = null,
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public ?int $ascentFromStart = null,
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public ?int $descentFromStart = null,
        public ?\DateTimeImmutable $cutoffTime = null,
        public bool $assistanceAllowed = false,
    ) {
    }
}
