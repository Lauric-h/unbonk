<?php

namespace App\Application\Food\Exception;

final class InvalidIngestionTypeValueException extends \InvalidArgumentException
{
    public function __construct(string $ingestionType)
    {
        parent::__construct(\sprintf('"%s" is not a valid ingestion type value.', $ingestionType));
    }
}
