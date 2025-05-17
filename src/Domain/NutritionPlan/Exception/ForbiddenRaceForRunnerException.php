<?php

namespace App\Domain\NutritionPlan\Exception;

final class ForbiddenRaceForRunnerException extends \Exception
{
    public function __construct(string $raceId, string $runnerId)
    {
        parent::__construct(\sprintf('Runner %s cannot access race %s', $runnerId, $raceId));
    }
}
