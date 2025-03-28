<?php

namespace App\Application\Race\UseCase\UpdateRace;

use App\Domain\Race\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog)
    {
    }

    public function __invoke(UpdateRaceCommand $command): void
    {
        $race = $this->racesCatalog->getByIdAndRunnerId($command->id, $command->runnerId);

        $race->update(
            $command->name,
            $command->date,
            $command->distance,
            $command->elevationGain,
            $command->elevationLoss,
            $command->city,
            $command->postalCode
        );

        $this->racesCatalog->add($race);
    }
}
