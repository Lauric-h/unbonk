<?php

namespace App\Domain\NutritionPlan\Exception;

final class ForbiddenRaceAccessException extends \DomainException
{
    public function __construct(string $raceId, string $runnerId)
    {
        parent::__construct(\sprintf('Runner %s cannot access race %s', $runnerId, $raceId));
    }
}
