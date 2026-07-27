<?php

namespace App\Application\Race\Exception;

final class CatalogRaceNotFoundException extends \RuntimeException
{
    public function __construct(string $eventId, string $raceId)
    {
        parent::__construct(sprintf('Catalog race "%s" not found for event "%s".', $raceId, $eventId));
    }
}