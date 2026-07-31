<?php

namespace App\Application\Race\Exception;

final class RunnerRaceAccessDeniedException extends \RuntimeException
{
    public function __construct(string $runnerRaceId, string $runnerId)
    {
        parent::__construct(\sprintf('Race %s does not belong to %s', $runnerRaceId, $runnerId));
    }
}