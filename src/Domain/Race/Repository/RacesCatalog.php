<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\Race;

interface RacesCatalog
{
    public function add(Race $race): void;

    public function getByIdAndRunnerId(string $id, string $runnerId): Race;

    public function remove(Race $race): void;

    /**
     * @return Race[]
     */
    public function getAll(string $runnerId): array;
}
