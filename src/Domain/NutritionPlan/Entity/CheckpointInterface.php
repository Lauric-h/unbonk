<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\NutritionPlan\ValueObject\Cutoff;

interface CheckpointInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getLocation(): string;

    public function getDistanceFromStart(): int;

    public function getAscentFromStart(): int;

    public function getDescentFromStart(): int;

    public function getCutoff(): ?Cutoff;

    public function isAssistanceAllowed(): bool;

    public function getType(): CheckpointType;

    public function isEditable(): bool;
}
