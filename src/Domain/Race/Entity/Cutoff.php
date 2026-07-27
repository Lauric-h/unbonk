<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

final readonly class Cutoff
{
    public function __construct(
        public int $offsetInMinutes,
    ) {
        if ($this->offsetInMinutes <= 0) {
            throw new \DomainException('Cutoff offset must be strictly positive.');
        }
    }

    public function absoluteDateTime(\DateTimeImmutable $raceStartDateTime): \DateTimeImmutable
    {
        return $raceStartDateTime->modify(sprintf('+%d minutes', $this->offsetInMinutes));
    }
}