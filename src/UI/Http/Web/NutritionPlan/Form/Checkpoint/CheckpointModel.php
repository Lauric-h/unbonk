<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Form\Checkpoint;

use Symfony\Component\Validator\Constraints as Assert;

final class CheckpointModel
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name = '',
        #[Assert\NotBlank]
        public string $location = '',
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public int $distanceFromStart = 0,
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public int $ascentFromStart = 0,
        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public int $descentFromStart = 0,
        public ?\DateTimeImmutable $cutoffTime = null,
        public bool $assistanceAllowed = false,
    ) {
    }
}
