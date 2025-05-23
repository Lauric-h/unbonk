<?php

namespace App\Application\Race\UseCase\CreateRace;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateRaceCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\Uuid]
        public string $runnerId,
        public \DateTimeImmutable $date,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\GreaterThan(0)]
        public int $distance,
        #[Assert\PositiveOrZero]
        public int $elevationGain,
        #[Assert\PositiveOrZero]
        public int $elevationLoss,
        #[Assert\NotBlank]
        public string $city,
        public string $postalCode,
    ) {
    }
}
