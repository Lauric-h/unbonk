<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;

final readonly class Profile
{
    public function __construct(
        public Distance $distance,
        public Ascent $ascent,
        public Descent $descent,
    ) {
    }

    public static function create(int $distance, int $ascent, int $descent): self
    {
        return new self(
            new Distance($distance),
            new Ascent($ascent),
            new Descent($descent),
        );
    }
}
