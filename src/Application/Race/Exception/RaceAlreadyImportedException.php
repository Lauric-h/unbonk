<?php

namespace App\Application\Race\Exception;

final class RaceAlreadyImportedException extends \RuntimeException
{
    public function __construct(string $runnerId, string $raceId)
    {
        parent::__construct(sprintf('Race "%s" already imported for runner %s', $raceId, $runnerId));
    }
}