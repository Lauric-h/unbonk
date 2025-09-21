<?php

namespace App\Domain\NutritionPlan\Service;

use App\Domain\Race\Exception\ForbiddenRaceForRunnerException;
use App\Domain\Race\Port\RaceOwnershipPort;
use App\Domain\Race\Repository\NutritionPlansCatalog;

final readonly class NutritionPlanAccessService
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private RaceOwnershipPort $raceOwnershipPort,
    ) {
    }

    public function checkAccess(string $nutritionPlanId, string $runnerId): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($nutritionPlanId);
        if (false === $this->raceOwnershipPort->userOwnsRace($nutritionPlan->raceId, $runnerId)) {
            throw new ForbiddenRaceForRunnerException($nutritionPlan->raceId, $runnerId);
        }
    }
}
