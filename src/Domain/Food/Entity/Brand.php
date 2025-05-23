<?php

namespace App\Domain\Food\Entity;

use App\Domain\Food\Event\BrandCreated;
use App\Domain\Shared\DomainEventInterface;
use App\Domain\Shared\WithDomainEventInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Brand implements WithDomainEventInterface
{
    /**
     * @var DomainEventInterface[]
     */
    private array $events = [];

    /**
     * @param Collection<int, Food> $foods
     */
    public function __construct(
        public string $id,
        public string $name,
        public Collection $foods = new ArrayCollection(),
    ) {
        $this->recordEvent(new BrandCreated($id));
    }

    public function update(string $name): void
    {
        $this->name = $name;
    }

    public function addFood(Food $food): void
    {
        if (!$this->foods->contains($food)) {
            $this->foods[] = $food;
            $food->brand = $this;
        }
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
