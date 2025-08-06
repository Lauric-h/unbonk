<?php

namespace App\UI\Http\Web\Race\Form\CreateRace;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateRaceModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $name = null,
        #[Assert\NotBlank]
        public ?\DateTimeImmutable $date = null,
        #[Assert\Positive]
        public ?int $distance = null,
        #[Assert\PositiveOrZero]
        public ?int $ascent = null,
        #[Assert\PositiveOrZero]
        public ?int $descent = null,
        #[Assert\NotBlank]
        public ?string $city = null,
        #[Assert\NotBlank]
        public ?string $postalCode = null,
    ) {
    }
}
