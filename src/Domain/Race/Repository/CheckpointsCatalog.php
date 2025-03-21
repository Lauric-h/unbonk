<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\Checkpoint;

interface CheckpointsCatalog
{
    public function add(Checkpoint $checkpoint): void;

    public function remove(string $id, string $raceId, string $runnerId): void;

    public function get(string $id, string $raceId, string $runnerId): Checkpoint;
}
