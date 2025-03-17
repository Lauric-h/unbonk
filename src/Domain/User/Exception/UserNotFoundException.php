<?php

namespace App\Domain\User\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    public function __construct(string $identifier)
    {
        parent::__construct(\sprintf('User not found: %s', $identifier));
    }
}
