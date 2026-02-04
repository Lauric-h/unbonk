<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Repository;

use App\Domain\NutritionPlan\Entity\ImportedRace;

interface RacesCatalog
{
    public function add(ImportedRace $race): void;

    public function remove(ImportedRace $race): void;

    public function get(string $id): ImportedRace;

    /**
     * @return ImportedRace[]
     */
    public function findByRunnerId(string $runnerId): array;
}
