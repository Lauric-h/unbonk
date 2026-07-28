<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\ValueObject\Carbs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class SegmentNutritionPlan
{
    /**
     * @var Collection<int, NutritionItem>
     */
    private Collection $NutritionItems;

    private NutritionPlan $nutritionPlan;

    public function __construct(
        private readonly string $id,
        private readonly string $segmentId,
        private readonly int $order,
        private ?Carbs $targetCarbs = null,
    ) {
        $this->NutritionItems = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSegmentId(): string
    {
        return $this->segmentId;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getTargetCarbs(): ?Carbs
    {
        return $this->targetCarbs;
    }

    /** @internal Appelé uniquement par NutritionPlan::create(). */
    public function attachToPlan(NutritionPlan $nutritionPlan): void
    {
        $this->nutritionPlan = $nutritionPlan;
    }

    public function setTargetCarbs(?Carbs $targetCarbs): void
    {
        $this->targetCarbs = $targetCarbs;
    }

    /**
     * @return NutritionItem[]
     */
    public function getNutritionItems(): array
    {
        return $this->NutritionItems->toArray();
    }

    public function addNutritionItem(NutritionItem $NutritionItem): void
    {
        $NutritionItem->attachToSegmentPlan($this);
        $this->NutritionItems->add($NutritionItem);
    }

    public function removeNutritionItem(string $NutritionItemId): void
    {
        $NutritionItem = $this->NutritionItems->findFirst(
            static fn (int $key, NutritionItem $item): bool => $item->id() === $NutritionItemId
        );

        if (null === $NutritionItem) {
            throw new \DomainException(sprintf('Food item "%s" not found.', $NutritionItemId));
        }

        $this->NutritionItems->removeElement($NutritionItem);
    }

    public function totalCarbs(): Carbs
    {
        return array_reduce(
            $this->NutritionItems->toArray(),
            static fn (Carbs $total, NutritionItem $item): Carbs => $total->add($item->totalCarbs()),
            Carbs::zero(),
        );
    }
}
