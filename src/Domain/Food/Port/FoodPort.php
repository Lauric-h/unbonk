<?php

namespace App\Domain\Food\Port;

use App\Domain\Food\DTO\FoodDTO;

interface FoodPort
{
    public function getById(string $id): FoodDTO;
}
