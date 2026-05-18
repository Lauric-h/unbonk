<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Carbs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class NutritionPlan
{
    /**
     * @param Collection<int, SegmentNutritionPlan> $segmentPlans
     */
    public function __construct(
        public string $id,
        public RunnerRace $runnerRace,
        public ?string $name,
        public \DateTimeImmutable $createdAt,
        private Collection $segmentPlans = new ArrayCollection(),
    ) {
    }

    public static function createFromRunnerRace(
        string $id,
        RunnerRace $runnerRace,
        ?string $name = null,
    ): self {
        $nutritionPlan = new self(
            id: $id,
            runnerRace: $runnerRace,
            name: $name,
            createdAt: new \DateTimeImmutable(),
        );

        return $nutritionPlan;
    }

    /**
     * @return Collection<int, SegmentNutritionPlan>
     */
    public function getSegmentPlans(): Collection
    {
        return $this->segmentPlans;
    }

    public function getSegmentPlanBySegmentId(string $segmentId): ?SegmentNutritionPlan
    {
        return $this->segmentPlans->findFirst(
            static fn (int $key, SegmentNutritionPlan $plan) => $plan->segment->id === $segmentId
        );
    }

    public function addSegmentPlan(SegmentNutritionPlan $segmentPlan): void
    {
        if (!$this->segmentPlans->contains($segmentPlan)) {
            $this->segmentPlans->add($segmentPlan);
        }
    }

    public function removeSegmentPlan(string $segmentId): void
    {
        $segmentPlan = $this->getSegmentPlanBySegmentId($segmentId);

        if (null === $segmentPlan) {
            throw new \DomainException(\sprintf('Segment plan for segment %s not found', $segmentId));
        }

        $this->segmentPlans->removeElement($segmentPlan);
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }

    public function getTotalCarbs(): int
    {
        $total = 0;
        foreach ($this->segmentPlans as $segmentPlan) {
            $total += $segmentPlan->getTotalCarbsFromItems();
        }
        return $total;
    }

}
