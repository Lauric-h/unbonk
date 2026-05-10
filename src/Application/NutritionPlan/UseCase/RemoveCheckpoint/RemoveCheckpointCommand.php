<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\RemoveCheckpoint;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RemoveCheckpointCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\Uuid]
        public string $checkpointId,
    ) {
    }
}
