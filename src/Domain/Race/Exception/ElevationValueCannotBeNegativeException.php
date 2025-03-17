<?php

namespace App\Domain\Race\Exception;

final class ElevationValueCannotBeNegativeException extends \DomainException
{
    public function __construct(string $field, int $value)
    {
        parent::__construct(sprintf('Field %s value cannot be negative, got %s', $field, $value));
    }
}
