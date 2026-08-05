<?php

namespace App\Domain\NutritionPlan\Repository;

use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\Shared\Repository\ObjectRepositoryInterface;

/**
 * @extends ObjectRepositoryInterface<NutritionPlan>
 */
interface NutritionPlanRepositoryInterface extends ObjectRepositoryInterface
{
    public function existsForRunnerRace(string $runnerRaceId): bool;

    public function findByRunnerRaceId(string $runnerRaceId): ?NutritionPlan;

    public function deleteByRunnerRaceId(string $runnerRaceId): void;
}
