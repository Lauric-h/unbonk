<?php

namespace App\Application\Race\Exception;

final class CatalogEventNotFoundException extends \RuntimeException
{
    public function __construct(string $eventId)
    {
        parent::__construct(sprintf('Catalog event "%s" not found.', $eventId));
    }
}