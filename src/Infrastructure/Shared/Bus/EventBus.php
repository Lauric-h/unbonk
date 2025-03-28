<?php

namespace App\Infrastructure\Shared\Bus;

use App\Domain\Shared\Bus\EventBusInterface;
use App\Domain\Shared\Event\Event;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

readonly class EventBus implements EventBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(Event $event): void
    {
        $this->dispatch($event);
    }

    public function dispatch(Event $event): void
    {
        $this->messageBus->dispatch($event);
    }

    public function dispatchAfterCurrentBusHasFinished(Event $event): void
    {
        $this->messageBus->dispatch(
            new Envelope($event))
            ->with(new DispatchAfterCurrentBusStamp());
    }
}
