<?php

namespace App\Application\NutritionPlan\UseCase\GetEvent;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetEventQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $eventId
    ) {
    }
}
