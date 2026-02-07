<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

enum CheckpointType: string
{
    case AidStation = 'aid_station';
    case Intermediate = 'intermediate';
    case StartCheckpoint = 'start_checkpoint';
    case FinishCheckpoint = 'finish_checkpoint';

    public function isEditable(): bool
    {
        return self::Intermediate === $this;
    }
}
