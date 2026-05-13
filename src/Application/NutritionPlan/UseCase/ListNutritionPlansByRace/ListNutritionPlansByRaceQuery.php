<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\ListNutritionPlansByRace;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ListNutritionPlansByRaceQuery implements QueryInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $raceId,
    ) {
    }
}
