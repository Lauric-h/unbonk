<?php

namespace App\Application\NutritionPlan\UseCase\CreateSegments;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Domain\Shared\Bus\CommandInterface;
use App\Infrastructure\Shared\Bus\UserAwareInterface;
use App\Infrastructure\Shared\Bus\UserAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateSegmentsCommand implements CommandInterface, UserAwareInterface
{
    use UserAwareTrait;

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
