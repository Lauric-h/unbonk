<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;

final readonly class Profile
{
    private function __construct(
        public int $distance,
        public int $ascent,
        public int $descent,
    ) {
    }

    public static function create(Distance $distance, Ascent $ascent, Descent $descent): self
    {
        return new self(
            $distance->value,
            $ascent->value,
            $descent->value,
        );
    }
}
