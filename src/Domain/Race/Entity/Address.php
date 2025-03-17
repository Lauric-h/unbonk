<?php

namespace App\Domain\Race\Entity;

final readonly class Address
{
    public function __construct(
        public string $city,
        public string $postalCode,
    ) {
    }
}
