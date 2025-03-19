<?php

namespace App\UI\Http\Rest\Race\View;

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
            distance: $profile->distance,
            elevationGain: $profile->elevationGain,
            elevationLoss: $profile->elevationLoss,
        );
    }
}
