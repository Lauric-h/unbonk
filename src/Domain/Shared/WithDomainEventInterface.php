<?php

namespace App\Domain\Shared;

interface WithDomainEventInterface
{
    public function recordEvent(DomainEventInterface $event): void;

    /**
     * @return DomainEventInterface[]
     */
    public function pullEvents(): array;
}
