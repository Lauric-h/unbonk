<?php

namespace App\Application\Shared;

use App\Domain\Shared\DomainEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class DomainEventDispatcher
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @param DomainEventInterface[] $events
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
