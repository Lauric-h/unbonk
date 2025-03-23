<?php

namespace App\Domain\Race\Exception;

final class CheckpointWithSameDistanceException extends \DomainException
{
    public function __construct(int $distance)
    {
        parent::__construct(\sprintf('Checkpoint already exists at distance %d', $distance));
    }
}
