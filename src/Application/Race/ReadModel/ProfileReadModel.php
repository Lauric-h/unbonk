<?php

namespace App\Application\Race\ReadModel;

use App\Domain\Race\Entity\Profile;

final class ProfileReadModel
{
    public function __construct(
        public int $distance,
        public int $elevationGain,
        public int $elevationLoss,
    ) {
    }

    public static function fromDomain(Profile $profile): self
    {
        return new self(
            distance: $profile->distance->value,
            elevationGain: $profile->ascent->value,
            elevationLoss: $profile->descent->value,
        );
    }
}
