<?php

namespace App\Application\Race\UseCase\DeleteRace;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class DeleteRaceCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
