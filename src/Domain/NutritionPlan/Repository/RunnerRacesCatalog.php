<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Repository;

use App\Domain\NutritionPlan\Entity\RunnerRace;

interface RunnerRacesCatalog
{
    public function add(RunnerRace $race): void;

    public function remove(RunnerRace $race): void;

    public function get(string $id): RunnerRace;

    /**
     * @return RunnerRace[]
     */
    public function findByRunnerId(string $runnerId): array;
}
