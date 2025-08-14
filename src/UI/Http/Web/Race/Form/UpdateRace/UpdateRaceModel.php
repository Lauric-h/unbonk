<?php

namespace App\UI\Http\Web\Race\Form\UpdateRace;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateRaceModel
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public \DateTimeImmutable $date,
        #[Assert\Positive]
        public int $distance,
        #[Assert\PositiveOrZero]
        public int $ascent,
        #[Assert\PositiveOrZero]
        public int $descent,
        #[Assert\NotBlank]
        public string $city,
        #[Assert\NotBlank]
        public string $postalCode,
    ) {
    }
}
