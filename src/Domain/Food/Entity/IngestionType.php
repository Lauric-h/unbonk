<?php

namespace App\Domain\Food\Entity;

enum IngestionType: string
{
    case Liquid = 'liquid';
    case Solid = 'solid';
    case SemiLiquid = 'semi_liquid';
}
