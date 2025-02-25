<?php

namespace App\Domain\Food\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class FoodNotFoundException extends NotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct(\sprintf('Food with id "%s" not found', $id));
    }
}
