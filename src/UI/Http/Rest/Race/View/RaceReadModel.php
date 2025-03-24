<?php

namespace App\UI\Http\Rest\Race\View;

use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\Race;

final class RaceReadModel
{
    /**
     * @param CheckpointReadModel[] $checkpoints
     */
    public function __construct(
        public string $id,
        public string $date,
        public string $name,
        public ProfileReadModel $profile,
        public AddressReadModel $address,
        public string $runnerId,
        public array $checkpoints = [],
    ) {
    }

    public static function fromRace(Race $race): self
    {
        return new self(
            $race->id,
            $race->date->format('Y-m-d'),
            $race->name,
            ProfileReadModel::fromDomain($race->profile),
            AddressReadModel::fromDomain($race->address),
            $race->runnerId,
            array_map(
                static fn (Checkpoint $checkpoint) => CheckpointReadModel::fromCheckpoint($checkpoint),
                $race->checkpoints->toArray(),
            )
        );
    }
}
