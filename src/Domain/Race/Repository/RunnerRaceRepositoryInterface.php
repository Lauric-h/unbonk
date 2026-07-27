<?php

namespace App\Domain\Race\Repository;

use App\Domain\Race\Entity\RunnerRace;
use App\Domain\Shared\Repository\ObjectRepositoryInterface;

/**
 * @extends ObjectRepositoryInterface<RunnerRace>
 */
interface RunnerRaceRepositoryInterface extends ObjectRepositoryInterface
{
    public function existsForRunner(string $runnerId, string $sourceRaceId): bool;
}