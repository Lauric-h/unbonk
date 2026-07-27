<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

enum CheckpointType: string
{
    case Start = 'start';
    case Intermediate = 'intermediate';
    case Finish = 'finish';
}