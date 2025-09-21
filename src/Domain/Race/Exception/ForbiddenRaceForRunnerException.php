<?php

namespace App\Domain\Race\Exception;

final class ForbiddenRaceForRunnerException extends \Exception
{
    public function __construct(string $raceId, string $runnerId)
    {
        parent::__construct(\sprintf('Runner %s cannot access race %s', $runnerId, $raceId));
    }
}
