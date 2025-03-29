<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Application\NutritionPlan\UseCase\CreateSegments\CreateSegmentsCommand;
use App\Application\NutritionPlan\UseCase\CreateSegments\CreateSegmentsCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Factory\SegmentFactoryInterface;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class CreateSegmentsCommandHandlerTest extends TestCase
{
    public function testCreateSegments(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $factory = $this->createMock(SegmentFactoryInterface::class);
        $handler = new CreateSegmentsCommandHandler($repository, $factory);

        $npId = 'npId';

        $points = [
            new PointDTO(
                'externalRef',
                10,
                100,
                1000,
                1000
            ),
            new PointDTO(
                'externalRef2',
                20,
                200,
                2000,
                2000
            ),
        ];

        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $factory->expects(self::exactly(1))
            ->method('createFromPoints');

        $command = new CreateSegmentsCommand($npId, $points);

        $repository->expects($this->once())
            ->method('get')
            ->with($npId)
            ->willReturn($nutritionPlan);

        $repository->expects($this->once())
            ->method('add')
            ->with($nutritionPlan);

        ($handler)($command);
    }
}
