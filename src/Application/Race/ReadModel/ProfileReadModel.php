<?php

namespace App\Application\Race\ReadModel;

use App\Domain\Race\Entity\Profile;

final class ProfileReadModel
{
    public function __construct(
        public int $distance,
        public int $ascent,
        public int $descent,
    ) {
    }

    public static function fromDomain(Profile $profile): self
    {
        return new self(
            distance: $profile->distance,
            ascent: $profile->ascent,
            descent: $profile->descent,
        );
    }
}
