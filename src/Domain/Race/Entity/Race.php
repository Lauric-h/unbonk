<?php

namespace App\Domain\Race\Entity;

final class Race
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $date,
        public string $name,
        public Profile $profile,
        public Address $address,
        public string $runnerId,
    ) {
    }
}
