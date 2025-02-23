<?php

namespace App\Infrastructure\Food\EventListener;

use App\Domain\Food\Event\BrandCreated;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class BrandListener
{
    #[AsEventListener]
    public function onBrandCreated(BrandCreated $event): void
    {
        // Keep as example for now
    }
}
