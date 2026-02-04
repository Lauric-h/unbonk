<?php

namespace App\Domain\NutritionPlan\ValueObject;

final readonly class Cutoff
{
    public function __construct(
        public \DateTimeImmutable $dateTime,
    ) {
    }

    public function getInMinutes(\DateTimeImmutable $raceStartDateTime): int
    {
        $interval = $raceStartDateTime->diff($this->dateTime);

        return ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
    }
}
