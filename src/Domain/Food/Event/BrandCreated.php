<?php

namespace App\Domain\Food\Event;

use App\Domain\Shared\DomainEventInterface;

final readonly class BrandCreated implements DomainEventInterface
{
    public function __construct(public string $id)
    {
    }
}
