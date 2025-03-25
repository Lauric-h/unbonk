<?php

namespace App\Domain\Race\Entity;

enum CheckpointType: string
{
    case Start = 'start';
    case Finish = 'finish';
    case AidStation = 'aid_station';
    case Intermediate = 'intermediate';
}
