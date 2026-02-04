<?php

namespace App\Application\NutritionPlan\UseCase\DeleteUserRace;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteUserRaceCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $runnerId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $raceId,
    ) {
    }
}
