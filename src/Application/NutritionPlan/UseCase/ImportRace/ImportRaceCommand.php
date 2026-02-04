<?php

namespace App\Application\NutritionPlan\UseCase\ImportRace;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ImportRaceCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $externalEventId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $externalRaceId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
