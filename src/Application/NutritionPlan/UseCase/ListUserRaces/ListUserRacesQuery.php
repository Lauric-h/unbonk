<?php

namespace App\Application\NutritionPlan\UseCase\ListUserRaces;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ListUserRacesQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $userId,
    ) {
    }
}
