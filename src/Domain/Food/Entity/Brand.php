<?php

namespace App\Domain\Food\Entity;

use App\Domain\Food\Event\BrandCreated;
use App\Domain\Shared\DomainEventInterface;
use App\Domain\Shared\WithDomainEventInterface;

class Brand implements WithDomainEventInterface
{
    /**
     * @var DomainEventInterface[]
     */
    private array $events = [];

    /**
     * @param Food[] $foods
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $foods = [],
    ) {
        $this->recordEvent(new BrandCreated($id));
    }

    public function update(string $name): void
    {
        $this->name = $name;
    }

    public function recordEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
