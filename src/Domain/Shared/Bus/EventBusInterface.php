<?php

namespace App\Domain\Shared\Bus;

use App\Domain\Shared\Event\Event;

interface EventBusInterface
{
    public function dispatchAfterCurrentBusHasFinished(Event $event): void;
}
