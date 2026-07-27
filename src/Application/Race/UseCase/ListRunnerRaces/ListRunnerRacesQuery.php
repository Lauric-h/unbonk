<?php

namespace App\Application\Race\UseCase\ListRunnerRaces;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class ListRunnerRacesQuery implements QueryInterface
{
    public function __construct(public string $runnerId)
    {
    }
}