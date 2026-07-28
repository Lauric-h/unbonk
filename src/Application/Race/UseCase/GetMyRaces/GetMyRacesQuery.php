<?php

namespace App\Application\Race\UseCase\GetMyRaces;

use App\Domain\Shared\Bus\QueryInterface;

final readonly class GetMyRacesQuery implements QueryInterface
{
    public function __construct(public string $runnerId)
    {
    }
}