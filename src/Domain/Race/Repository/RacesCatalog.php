<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\Race;

interface RacesCatalog
{
    public function add(Race $race): void;

    public function get(string $id): Race;

    public function remove(string $id): void;

    /**
     * @return Race[]
     */
    public function getAll(string $runnerId): array;
}
