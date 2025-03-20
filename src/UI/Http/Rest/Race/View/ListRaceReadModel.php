<?php

namespace App\UI\Http\Rest\Race\View;

use App\Domain\Race\Entity\Race;

final class ListRaceReadModel
{
    /**
     * @param RaceReadModel[] $races
     */
    public function __construct(public array $races)
    {
    }

    /**
     * @param Race[] $races
     */
    public static function fromRaces(array $races): self
    {
        return new self(
            races: array_map(
                static fn ($race) => RaceReadModel::fromRace($race),
                $races
            ));
    }
}
