<?php

namespace App\Application\NutritionPlan\UseCase\CreateSegments;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Entity\SegmentPoint;
use App\Domain\NutritionPlan\Factory\SegmentFactoryInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use Doctrine\Common\Collections\ArrayCollection;

final readonly class CreateSegmentsCommandHandler implements CommandHandlerInterface
{
    public function __construct(private NutritionPlansCatalog $nutritionPlansCatalog, private SegmentFactoryInterface $segmentFactory)
    {
    }

    public function __invoke(CreateSegmentsCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $points = $command->points;
        usort($points, static fn (PointDTO $a, PointDTO $b) => $a->distance <=> $b->distance);

        $segmentPoints = array_map(
            static fn (PointDTO $point): SegmentPoint => $point->toSegmentPoint(),
            $points
        );

        $segments = new ArrayCollection([]);
        for ($i = 0; $i < (count($segmentPoints) - 1); ++$i) {
            $start = $segmentPoints[$i];
            $finish = $segmentPoints[$i + 1];

            /** @var ?Segment $existingSegment */
            $existingSegment = $nutritionPlan->getSegmentByStartId($start->externalReference);
            if (null !== $existingSegment) {
                $segment = $this->segmentFactory->createWithNutritionData(
                    startPoint: $start,
                    finishPoint: $finish,
                    carbs: $existingSegment->carbsTarget,
                    nutritionPlan: $nutritionPlan,
                    nutritionItems: $existingSegment->nutritionItems
                );
            } else {
                $segment = $this->segmentFactory->createFromPoints($start, $finish, $nutritionPlan);
            }

            $segments->add($segment);
        }

        $nutritionPlan->replaceAllSegments($segments);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
