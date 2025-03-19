<?php

namespace App\Domain\Race\Exception;

final class RaceDoesNotBelongToRunnerException extends \DomainException
{
    public function __construct(string $raceId, string $runnerId)
    {
        parent::__construct(\sprintf('Race %s does not belong to runner %s', $raceId, $runnerId));
    }
}
