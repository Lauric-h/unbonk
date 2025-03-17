<?php

namespace App\Domain\Race\Exception;

final class DistanceCannotBeNegativeException extends \DomainException
{
    public function __construct(int $value)
    {
        parent::__construct(sprintf('Race distance cannot be negative, got %s', $value));
    }
}
