<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Request;

final readonly class UpdateCheckpointRequest
{
    public function __construct(
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public int $ascentFromStart,
        public int $descentFromStart,
        public ?string $cutoffTime,
        public bool $assistanceAllowed,
    ) {
    }
}
