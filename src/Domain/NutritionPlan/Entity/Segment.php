<?php

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Segment
{
    /**
     * @param Collection<int, NutritionItem> $nutritionItems
     */
    public function __construct(
        public string $id,
        public string $startId,
        public string $finishId,
        public Distance $distance,
        public Ascent $ascent,
        public Descent $descent,
        public Duration $estimatedTimeInMinutes,
        public Carbs $carbsTarget,
        public NutritionPlan $nutritionPlan,
        public Collection $nutritionItems = new ArrayCollection(),
    ) {
    }

    public function addNutritionItem(NutritionItem $nutritionItem): void
    {
        $existingNutritionItem = $this->getNutritionItemByExternalReference($nutritionItem->externalReference);
        if (null !== $existingNutritionItem) {
            $this->nutritionItems->removeElement($existingNutritionItem);
        }

        $this->nutritionItems->add($nutritionItem);
    }

    public function getNutritionItemByExternalReference(string $externalReference): ?NutritionItem
    {
        return $this->nutritionItems->findFirst(static fn (int $key, NutritionItem $nutritionItem) => $nutritionItem->externalReference === $externalReference);
    }
}
