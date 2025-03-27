<?php

namespace App\Infrastructure\Shared\Bus;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus
{
    use HandleTrait;

    public function __construct(
        /* @phpstan-ignore-next-line required to use HandleTrait */
        private MessageBusInterface $messageBus,
    ) {
    }

    public function query(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
