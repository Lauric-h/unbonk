<?php

namespace App\Application\NutritionPlan\Factory;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Entity\SegmentPoint;
use App\Domain\NutritionPlan\Factory\SegmentFactoryInterface;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\Collection;

final readonly class SegmentFactory implements SegmentFactoryInterface
{
    public function __construct(private IdGeneratorInterface $idGenerator)
    {
    }

    public function createFromPoints(SegmentPoint $startPoint, SegmentPoint $finishPoint, NutritionPlan $nutritionPlan): Segment
    {
        return new Segment(
            $this->idGenerator->generate(),
            $startPoint->externalReference,
            $finishPoint->externalReference,
            $this->computeDistance($startPoint->distance, $finishPoint->distance),
            $this->computeAscent($startPoint->ascent, $finishPoint->ascent),
            $this->computeDescent($startPoint->descent, $finishPoint->descent),
            $this->computeDuration($startPoint->estimatedDuration, $finishPoint->estimatedDuration),
            new Carbs(0),
            $nutritionPlan,
        );
    }

    /**
     * @param Collection<int, NutritionItem> $nutritionItems
     */
    public function createWithNutritionData(SegmentPoint $startPoint, SegmentPoint $finishPoint, Carbs $carbs, NutritionPlan $nutritionPlan, Collection $nutritionItems): Segment
    {
        return new Segment(
            $this->idGenerator->generate(),
            $startPoint->externalReference,
            $finishPoint->externalReference,
            $this->computeDistance($startPoint->distance, $finishPoint->distance),
            $this->computeAscent($startPoint->ascent, $finishPoint->ascent),
            $this->computeDescent($startPoint->descent, $finishPoint->descent),
            $this->computeDuration($startPoint->estimatedDuration, $finishPoint->estimatedDuration),
            $carbs,
            $nutritionPlan,
            $nutritionItems
        );
    }

    private function computeDistance(Distance $start, Distance $finish): Distance
    {
        return new Distance($finish->value - $start->value);
    }

    private function computeAscent(Ascent $start, Ascent $finish): Ascent
    {
        return new Ascent($finish->value - $start->value);
    }

    private function computeDescent(Descent $start, Descent $finish): Descent
    {
        return new Descent($finish->value - $start->value);
    }

    private function computeDuration(Duration $start, Duration $finish): Duration
    {
        return new Duration($finish->minutes - $start->minutes);
    }
}
