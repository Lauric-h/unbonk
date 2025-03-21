<?php

namespace App\Domain\Race\Exception;

final class EstimatedTimeCannotBeNegativeException extends \DomainException
{
    public function __construct(int $estimatedTimeInMinutes)
    {
        parent::__construct(\sprintf('Time cannot be negative, got %s', $estimatedTimeInMinutes));
    }
}
