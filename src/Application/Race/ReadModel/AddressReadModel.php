<?php

namespace App\Application\Race\ReadModel;

use App\Domain\Race\Entity\Address;

final class AddressReadModel
{
    public function __construct(
        public string $city,
        public string $postalCode,
    ) {
    }

    public static function fromDomain(Address $address): self
    {
        return new self(
            $address->city,
            $address->postalCode,
        );
    }
}
