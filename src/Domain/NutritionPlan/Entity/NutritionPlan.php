<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class NutritionPlan
{
    /**
     * @var Collection<int, SegmentNutritionPlan>
     */
    private Collection $segmentPlans;

    private function __construct(
        private readonly string $id,
        private readonly string $runnerRaceId,
        private ?string $name,
        private readonly \DateTimeImmutable $createdAt,
    ) {
        $this->segmentPlans = new ArrayCollection();
    }

    /**
     * @param string[]               $orderedSegmentIds Segments de la course, dans l'ordre, tels que
     *                                                    fournis par l'Application (via RunnerRaceReaderInterface)
     * @param callable(int): string $idGenerator
     */
    public static function create(
        string $id,
        string $runnerRaceId,
        array $orderedSegmentIds,
        callable $idGenerator,
        ?string $name = null,
    ): self {
        if ([] === $orderedSegmentIds) {
            throw new \DomainException('Cannot create a nutrition plan without any segment.');
        }

        $plan = new self(
            id: $id,
            runnerRaceId: $runnerRaceId,
            name: $name,
            createdAt: new \DateTimeImmutable(),
        );

        foreach (array_values($orderedSegmentIds) as $order => $segmentId) {
            $segmentPlan = new SegmentNutritionPlan(
                id: $idGenerator($order),
                segmentId: $segmentId,
                order: $order,
            );

            $segmentPlan->attachToPlan($plan);
            $plan->segmentPlans->add($segmentPlan);
        }

        return $plan;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRunnerRaceId(): string
    {
        return $this->runnerRaceId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return SegmentNutritionPlan[]
     */
    public function getSegmentPlans(): array
    {
        return $this->segmentPlans->toArray();
    }

    public function getSegmentPlan(string $segmentId): SegmentNutritionPlan
    {
        $segmentPlan = $this->segmentPlans->findFirst(
            static fn (int $key, SegmentNutritionPlan $plan): bool => $plan->getSegmentId() === $segmentId
        );

        if (null === $segmentPlan) {
            throw new \DomainException(sprintf('Segment plan for segment "%s" not found.', $segmentId));
        }

        return $segmentPlan;
    }

    public function setSegmentTarget(string $segmentId, ?Carbs $targetCarbs): void
    {
        $this->getSegmentPlan($segmentId)->setTargetCarbs($targetCarbs);
    }

    public function addNutritionItem(string $segmentId, NutritionItem $nutritionItem): void
    {
        $this->getSegmentPlan($segmentId)->addNutritionItem($nutritionItem);
    }

    public function removeNutritionItem(string $segmentId, string $nutritionItemId): void
    {
        $this->getSegmentPlan($segmentId)->removeNutritionItem($nutritionItemId);
    }

    public function totalCarbs(): Carbs
    {
        return array_reduce(
            $this->segmentPlans->toArray(),
            static fn (Carbs $total, SegmentNutritionPlan $segmentPlan): Carbs => $total->add($segmentPlan->totalCarbs()),
            Carbs::zero(),
        );
    }
}
