<?php

namespace App\Infrastructure\Shared\Bus;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus
{
    use MessageBusExceptionTrait;

    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
