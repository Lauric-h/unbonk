<?php

namespace App\Domain\Race\Entity;

class Race
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

    public function update(
        string $name,
        \DateTimeImmutable $date,
        int $distance,
        int $elevationGain,
        int $elevationLoss,
        string $city,
        string $postalCode,
    ): void {
        $this->name = $name;
        $this->date = $date;

        $profile = new Profile($distance, $elevationGain, $elevationLoss);
        $this->profile = $profile;

        $address = new Address($city, $postalCode);
        $this->address = $address;
    }
}
