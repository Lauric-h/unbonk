<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;

final readonly class SegmentNutritionPlanReadModel
{
    /**
     * @param NutritionItemReadModel[] $items
     */
    public function __construct(
        public string $id,
        public SegmentReadModel $segment,
        public ?int $targetCarbs,
        public array $items = [],
    ) {
    }

    public static function fromSegmentNutritionPlan(SegmentNutritionPlan $segmentNutritionPlan): self
    {
        $items = array_map(
            static fn (NutritionItem $item) => NutritionItemReadModel::fromNutritionItem($item),
            $segmentNutritionPlan->getItems()->toArray()
        );

        return new self(
            id: $segmentNutritionPlan->id,
            segment: SegmentReadModel::fromSegment($segmentNutritionPlan->segment),
            targetCarbs: $segmentNutritionPlan->targetCarbs?->value,
            items: $items,
        );
    }
}
