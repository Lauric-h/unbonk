<?php

namespace App\Application\Race\UseCase\CreateRace;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Repository\RacesCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class CreateRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RacesCatalog $racesCatalog, private IdGeneratorInterface $idGenerator)
    {
    }

    public function __invoke(CreateRaceCommand $command): void
    {
        $race = Race::create(
            id: $command->id,
            date: $command->date,
            name: $command->name,
            profile: new Profile($command->distance, $command->elevationGain, $command->elevationLoss),
            address: new Address($command->city, $command->postalCode),
            runnerId: $command->runnerId,
            startCheckpointId: $this->idGenerator->generate(),
            finishCheckpointId: $this->idGenerator->generate(),
        );

        $this->racesCatalog->add($race);
    }
}
