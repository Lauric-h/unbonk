<?php

namespace App\Domain\Food\Exception;

use App\Domain\Shared\Exception\AlreadyExistsException;

final class BrandAlreadyExistsException extends AlreadyExistsException
{
    public function __construct(string $naem)
    {
        parent::__construct(\sprintf('Brand with name "%s" already exists', $naem));
    }
}
