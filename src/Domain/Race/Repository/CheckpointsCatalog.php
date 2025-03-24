<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\Checkpoint;

interface CheckpointsCatalog
{
    public function getByIdAndRaceId(string $id, string $raceId): Checkpoint;

    public function add(Checkpoint $checkpoint): void;
}
