<?php

namespace App\Domain\NutritionPlan\Exception;

use App\Domain\Shared\Exception\NotFoundException;

final class SegmentNotFoundException extends NotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct(\sprintf('Segment with id %s not found', $id));
    }
}
