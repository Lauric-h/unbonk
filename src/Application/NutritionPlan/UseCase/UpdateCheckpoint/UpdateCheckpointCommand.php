<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\UpdateCheckpoint;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateCheckpointCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\Uuid]
        public string $checkpointId,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $location,
        #[Assert\PositiveOrZero]
        public int $distanceFromStart,
        #[Assert\PositiveOrZero]
        public int $ascentFromStart,
        #[Assert\PositiveOrZero]
        public int $descentFromStart,
        public ?\DateTimeImmutable $cutoffTime,
        public bool $assistanceAllowed,
    ) {
    }
}
