<?php

namespace App\Domain\Race\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class CheckpointNotFoundException extends NotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct(\sprintf('Checkpoint not found %s', $id));
    }
}
