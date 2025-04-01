<?php

namespace App\Application\NutritionPlan\UseCase\CreateSegments;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateSegmentsCommand implements CommandInterface
{
    /**
     * @param PointDTO[] $points
     */
    public function __construct(
        #[Assert\Uuid]
        public string $nutritionPlanId,
        public array $points,
    ) {
    }
}
