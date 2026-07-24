<?php

namespace App\Application\NutritionPlan\UseCase\GetRunnerRace;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRunnerRaceQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
